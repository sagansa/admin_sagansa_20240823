<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Purchases;
use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PaymentReceipt;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Panel\PaymentReceiptResource\Pages;
use App\Filament\Resources\Panel\PaymentReceiptResource\RelationManagers;
use App\Models\DailySalary;
use App\Models\FuelService;
use App\Models\InvoicePurchase;
use App\Models\Supplier;
use Filament\Forms\Components\Radio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PaymentReceiptResource extends Resource
{
    protected static ?string $model = PaymentReceipt::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Purchases::class;

    // protected static ?string $navigationGroup = 'Purchase';

    public static function getModelLabel(): string
    {
        return __('crud.paymentReceipts.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.paymentReceipts.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.paymentReceipts.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([

                    Radio::make('payment_for')
                        ->hiddenLabel()
                        ->options([
                            '1' => 'fuel/service',
                            '2' => 'daily salary',
                            '3' => 'invoice',
                        ])
                        ->inline()
                        ->reactive(),

                    Select::make('supplier_id')
                        ->label('Supplier')
                        ->visible(fn ($get) => $get('payment_for') != '2')
                        ->options(Supplier::all()->where('status', '<>', 3)->pluck('supplier_name', 'id'))
                        ->searchable()
                        ->native(false)
                        ->html(),

                    Select::make('user_id')
                        ->label('Employee')
                        ->visible(fn ($get) => $get('payment_for') == '2')
                        ->relationship('user', 'name', fn (Builder $query) => $query
                            ->whereHas('roles', fn (Builder $query) => $query
                                ->where('name', 'staff') || $query
                                ->where('name', 'supervisor')))
                        ->searchable()
                        ->native(false),

                    Select::make('fuelServices')
                        ->visible(fn ($get) => $get('payment_for') == '1')
                        ->multiple()
                        ->relationship(
                            name: 'fuelServices',
                            modifyQueryUsing: fn (Builder $query) => $query
                                ->where('payment_type_id', '1')
                                ->where('status', '1')
                                ->orderBy('date', 'desc')
                        )
                        ->getOptionLabelFromRecordUsing(fn (FuelService $record) => "{$record->fuel_service_name}")
                        ->preload()
                        ->reactive()
                        ->native(false)
                        ->afterStateUpdated(function ($state, $set) {
                            $totalAmount = 0;
                            foreach ($state as $fuelServiceId) {
                                $fuelService = FuelService::find($fuelServiceId);
                                if ($fuelService) {
                                    $fuelService->status = 2;
                                    $fuelService->save();
                                    $totalAmount += $fuelService->amount;
                                }
                            }
                            $set('total_amount', $totalAmount);
                        }),

                    Select::make('dailySalaries')
                        ->visible(fn ($get) => $get('payment_for') == '2')
                        ->multiple()
                        ->relationship(
                            name: 'dailySalaries',
                            modifyQueryUsing: fn (Builder $query) => $query
                                ->where('payment_type_id', '1')
                                ->where('status', '3')
                                ->orderBy('date', 'desc')
                        )
                        ->getOptionLabelFromRecordUsing(fn (DailySalary $record) => "{$record->daily_salary_name}")
                        ->preload()
                        ->reactive()
                        ->native(false)
                        ->afterStateUpdated(function ($state, $set) {
                            $totalAmount = 0;
                            foreach ($state as $dailySalaryId) {
                                $dailySalary = DailySalary::find($dailySalaryId);
                                if ($dailySalary) {
                                    $dailySalary->status = 2;
                                    $dailySalary->save();
                                    $totalAmount += $dailySalary->amount;
                                }
                            }
                            $set('total_amount', $totalAmount);
                        }),

                    Select::make('invoicePurchases')
                        ->visible(fn ($get) => $get('payment_for') == '3')
                        ->multiple()
                        ->relationship(
                            name: 'invoicePurchases',
                            modifyQueryUsing: fn (Builder $query) => $query
                                ->where('payment_type_id', '1')
                                ->where('payment_status', '1')
                                ->orderBy('date', 'desc')
                        )
                        ->getOptionLabelFromRecordUsing(fn (InvoicePurchase $record) => "{$record->invoice_purchase_name}")
                        ->preload()
                        ->reactive()
                        ->searchable()
                        ->native(false)
                        ->afterStateUpdated(function ($state, $set) {
                            $totalAmount = 0;
                            foreach ($state as $invoicePurchaseId) {
                                $invoicePurchase = InvoicePurchase::find($invoicePurchaseId);
                                if ($invoicePurchase) {
                                    $invoicePurchase->payment_status = 2;
                                    $invoicePurchase->save();
                                    $totalAmount += $invoicePurchase->total_price;
                                }
                            }
                            $set('total_amount', $totalAmount);
                        }),

                ]),
            ]),

            Section::make()->schema([
                Grid::make(['default' => 1])->schema([

                    TextInput::make('total_amount')
                        ->hiddenLabel()
                        ->readOnly()
                        ->default(0)
                        ->placeholder('Total Amount')
                        ->required()
                        ->numeric()
                        ->prefix('Rp'),

                    TextInput::make('transfer_amount')
                        ->hiddenLabel()
                        ->prefix('Rp')
                        ->required()
                        ->default(0)
                        ->numeric()
                        ->placeholder('Transfer Amount'),

                    ImageInput::make('image')
                        ->disk('public')
                        ->directory('images/PaymentReceipt'),

                    ImageInput::make('image_adjust')
                        ->disk('public')
                        ->directory('images/PaymentReceipt')
                        ->hidden(fn ($operation) => $operation === 'create'),

                    Notes::make('notes')
                        ->hidden(fn ($operation) => $operation === 'create'),

                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $paymentReceipt = PaymentReceipt::query();

        if (Auth::user()->hasRole('staff') || Auth::user()->hasRole('supervisor')) {
            $paymentReceipt->where('payment_for', '<>', 2);
        }

        return $table
            ->query($paymentReceipt)
            ->poll('60s')
            ->columns([
                ImageOpenUrlColumn::make('image')
                    ->label('Payment')
                    ->visibility('public')
                    ->url(fn($record) => asset('storage/' . $record->image)),

                ImageOpenUrlColumn::make('image_adjust')
                    ->label('Adjust')
                    ->visibility('public')
                    ->url(fn($record) => asset('storage/' . $record->image_adjust)),

                TextColumn::make('created_at')
                    ->date(),

                TextColumn::make('payment_for')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'fuel service',
                            '2' => 'daily salary',
                            '3' => 'invoice purchase',
                    }),

                CurrencyColumn::make('total_amount'),

                CurrencyColumn::make('transfer_amount'),

            ])

            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => self::calculateDifference($record) !== 0),
                Tables\Actions\ViewAction::make()
                    ->visible(fn ($record) => self::calculateDifference($record) === 0),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\FuelServicesRelationManager::class,
            RelationManagers\DailySalariesRelationManager::class,
            RelationManagers\InvoicePurchasesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentReceipts::route('/'),
            'create' => Pages\CreatePaymentReceipt::route('/create'),
            'view' => Pages\ViewPaymentReceipt::route('/{record}'),
            'edit' => Pages\EditPaymentReceipt::route('/{record}/edit'),
        ];
    }

    public static function calculateDifference($record)
    {
        return $record->total_amount - $record->transfer_amount;
    }
}
