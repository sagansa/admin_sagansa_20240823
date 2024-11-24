<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Purchases;
use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\SupplierColumn;
use App\Filament\Forms\CurrencyInput;
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
use App\Filament\Resources\Panel\PaymentReceiptResource\Pages;
use App\Filament\Resources\Panel\PaymentReceiptResource\RelationManagers;
use App\Models\DailySalary;
use App\Models\FuelService;
use App\Models\InvoicePurchase;
use App\Models\Supplier;
use Filament\Forms\Components\Radio;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
                        ->disabled(fn($operation) => $operation === 'edit')
                        ->options([
                            '1' => 'fuel/service',
                            '2' => 'daily salary',
                            '3' => 'invoice',
                        ])
                        ->inline()
                        ->reactive(),

                    Select::make('fuelServices')
                        ->visible(fn($get) => $get('payment_for') == '1')
                        ->required(fn($get) => $get('payment_for') == '1' && fn($operation) => $operation === 'create')
                        ->hidden(fn($operation) => $operation === 'edit' || $operation === 'view')
                        ->multiple()
                        ->relationship(
                            name: 'fuelServices',
                            modifyQueryUsing: fn(Builder $query) => $query
                                ->where('payment_type_id', '1')
                                ->where('status', '1')
                                ->orderBy('date', 'desc')
                        )
                        ->getOptionLabelFromRecordUsing(fn(FuelService $record) => "{$record->fuel_service_name}")
                        ->preload(),
                    // ->reactive()
                    // ->afterStateUpdated(function ($state, $set) {
                    //     $totalAmount = 0;
                    //     foreach ($state as $fuelServiceId) {
                    //         $fuelService = FuelService::find($fuelServiceId);
                    //         if ($fuelService) {
                    //             $totalAmount += $fuelService->amount;
                    //         }
                    //     }
                    //     $set('total_amount', $totalAmount);
                    // }),

                    Select::make('dailySalaries')
                        ->visible(fn($get) => $get('payment_for') == '2')
                        ->required(fn($get) => $get('payment_for') == '2' && fn($operation) => $operation === 'create')
                        ->hidden(fn($operation) => $operation === 'edit' || $operation === 'view')
                        ->multiple()
                        ->relationship(
                            name: 'dailySalaries',
                            modifyQueryUsing: fn(Builder $query) => $query
                                ->where('payment_type_id', '1')
                                ->where('status', '3')
                                ->orderBy('date', 'desc')
                        )
                        ->getOptionLabelFromRecordUsing(fn(DailySalary $record) => "{$record->daily_salary_name}")
                        ->preload(),
                    // ->reactive()
                    // ->afterStateUpdated(function ($state, $set) {
                    //     $totalAmount = 0;
                    //     foreach ($state as $dailySalaryId) {
                    //         $dailySalary = DailySalary::find($dailySalaryId);
                    //         if ($dailySalary) {
                    //             // $dailySalary->status = 2;
                    //             // $dailySalary->save();
                    //             $totalAmount += $dailySalary->amount;
                    //         }
                    //     }
                    //     $set('total_amount', $totalAmount);
                    // }),

                    Select::make('user_id')
                        ->label('Employee')
                        ->visible(fn($get) => $get('payment_for') == '2')
                        ->required(fn($get) => $get('payment_for') == '2')
                        ->relationship('user', 'name', fn(Builder $query) => $query
                            ->whereHas('roles', fn(Builder $query) => $query
                                ->where('name', 'staff') || $query
                                ->where('name', 'supervisor'))->orderBy('name', 'asc'))
                        ->searchable()
                        ->preload(),

                    Select::make('invoicePurchases')
                        ->visible(fn($get) => $get('payment_for') == '3')
                        ->required(fn($get) => $get('payment_for') == '3' && fn($operation) => $operation === 'create')
                        ->hidden(fn($operation) => $operation === 'edit' || $operation === 'view')
                        ->multiple()
                        ->relationship(
                            name: 'invoicePurchases',
                            modifyQueryUsing: fn(Builder $query) => $query
                                ->where('payment_type_id', '1')
                                ->where('payment_status', '1')
                                ->orderBy('date', 'desc')
                        )
                        // ->relationship(
                        //     name: 'invoicePurchases',
                        //     modifyQueryUsing: fn(Builder $query) => $query
                        //         ->where('payment_type_id', '1')
                        //         ->where(function ($query) {
                        //             $query->where('payment_status', '1')
                        //                 ->orWhere(function ($subQuery) {
                        //                     $subQuery->where('payment_status', '2')
                        //                         ->whereExists(function ($existsQuery) {
                        //                             $existsQuery->selectRaw(1)
                        //                                 ->from('invoice_purchase_payment_receipt')
                        //                                 ->whereColumn('invoice_purchase_payment_receipt.invoice_purchase_id', '=', 'invoice_purchases.id');
                        //                         });
                        //                 });
                        //         })
                        //         ->orderBy('date', 'desc')
                        // )
                        ->getOptionLabelFromRecordUsing(fn(InvoicePurchase $record) => "{$record->invoice_purchase_name}")
                        ->preload()
                        ->searchable(),
                    // ->reactive()
                    // ->afterStateUpdated(function ($state, $set) {
                    //     $totalAmount = 0;
                    //     foreach ($state as $invoicePurchaseId) {
                    //         $invoicePurchase = InvoicePurchase::find($invoicePurchaseId);
                    //         if ($invoicePurchase) {
                    //             $totalAmount += $invoicePurchase->total_price;
                    //         }
                    //     }
                    //     $set('total_amount', $totalAmount);
                    // }),

                    Select::make('supplier_id')
                        ->label(__('crud.suppliers.itemTitle'))
                        ->visible(fn($get) => $get('payment_for') == '3' || $get('payment_for') == '1')
                        ->required(fn($get) => $get('payment_for') == '3' || $get('payment_for') == '1')
                        ->relationship(
                            name: 'supplier',
                            modifyQueryUsing: fn(Builder $query) => $query->where('status', '<>', '3')->orderBy('name', 'asc'),
                        )
                        ->getOptionLabelFromRecordUsing(fn(Supplier $record) => "{$record->supplier_name}")
                        ->searchable()
                        ->preload(),
                ]),
            ]),

            Section::make()->schema([
                Grid::make(['default' => 1])->schema([

                    CurrencyInput::make('transfer_amount'),

                    // CurrencyInput::make('total_amount')->readOnly(),

                    ImageInput::make('image')
                        ->directory('images/PaymentReceipt'),

                    ImageInput::make('image_adjust')
                        ->directory('images/PaymentReceipt')
                        ->hidden(fn($operation) => $operation === 'create'),

                    Notes::make('notes')
                        ->hidden(fn($operation) => $operation === 'create'),

                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageOpenUrlColumn::make('image')
                    ->label('Payment')
                    ->visibility('public')
                    ->url(fn($record) => asset('storage/' . $record->image)),

                ImageOpenUrlColumn::make('image_adjust')
                    ->label('Adjust')
                    ->visibility('public')
                    ->url(fn($record) => asset('storage/' . $record->image_adjust)),

                SupplierColumn::make('Supplier')
                    ->visible(fn($livewire) => $livewire->activeTab !== 'daily salary'),

                TextColumn::make('user.name')
                    ->label('Employee')
                    ->visible(fn($livewire) => $livewire->activeTab === 'daily salary'),

                TextColumn::make('created_at')
                    ->date(),

                CurrencyColumn::make('transfer_amount'),

                // Kolom untuk Invoice (payment_for = 3)
                TextColumn::make('invoicePurchases.date')
                    ->label('Date')
                    ->visible(fn($livewire) => $livewire->activeTab === 'invoice'),
                // ->formatStateUsing(function ($state, $record) {
                //     return $record->invoices->pluck('invoice_number')->join(', ');
                // }),

                // Kolom untuk Fuel Service (payment_for = 1)
                TextColumn::make('fuelServices.vehicle.no_register')
                    ->label('Fuel Service Invoice')
                    ->visible(fn($livewire) => $livewire->activeTab === 'fuel service'),
                // ->formatStateUsing(function ($state, $record) {
                //     return $record->invoicePurchases->pluck('invoice_number')->join(', ');
                // }),

                // Kolom untuk Daily Salary (payment_for = 2)
                TextColumn::make('dailySalaries.date')
                    ->label('Salary Date')
                    ->visible(fn($livewire) => $livewire->activeTab === 'daily salary')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->dailySalaries->pluck('date')
                            ->map(fn($date) => Carbon::parse($date)->format('d/m/Y'))
                            ->join(', ');
                    }),

                // Kolom detail yang lebih lengkap per tab
                TextColumn::make('payment_details')
                    ->label(function ($livewire) {
                        return match ($livewire->activeTab) {
                            'invoice' => 'Invoice Details',
                            'fuel service' => 'Fuel Service Details',
                            'daily salary' => 'Salary Details',
                            default => 'Details'
                        };
                    })
                    ->formatStateUsing(function ($state, $record, $livewire) {
                        return match ($livewire->activeTab) {
                            'invoice' => $record->invoices->map(function ($invoice) {
                                return "Invoice: {$invoice->invoice_number}<br>" .
                                    "Amount: " . number_format($invoice->total_amount, 0, ',', '.');
                            })->join('<br><br>'),

                            'fuel service' => $record->invoicePurchases->map(function ($invoice) {
                                return "Invoice: {$invoice->invoice_number}<br>" .
                                    "Amount: " . number_format($invoice->total_amount, 0, ',', '.');
                            })->join('<br><br>'),

                            'daily salary' => $record->dailySalaries->map(function ($salary) {
                                return "Date: " . Carbon::parse($salary->date)->format('d/m/Y') . "<br>" .
                                    "Amount: " . number_format($salary->amount, 0, ',', '.');
                            })->join('<br><br>'),

                            default => 'Details'
                        };
                    }),
            ])

            ->filters([])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
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
            RelationGroup::make('', [
                RelationManagers\FuelServicesRelationManager::class,
                RelationManagers\DailySalariesRelationManager::class,
                RelationManagers\InvoicePurchasesRelationManager::class,
            ])
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
}
