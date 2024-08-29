<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Forms\BaseSelectInput;
use App\Filament\Forms\CurrencyInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PaymentReceipt;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\PaymentReceiptResource\Pages;
use App\Filament\Resources\Panel\PaymentReceiptResource\RelationManagers;
use App\Models\DailySalary;
use App\Models\DailySalaryPaymentReceipt;
use App\Models\FuelService;
use App\Models\InvoicePurchase;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Support\Facades\Mail;

class PaymentReceiptResource extends Resource
{
    protected static ?string $model = PaymentReceipt::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Purchase';

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

    public function updateSections()
    {
        $paymentFor = request()->input('payment_for');

        // Tampilkan section berdasarkan nilai payment_for
        if ($paymentFor == '1') {
            // Tampilkan section fuel services
        } elseif ($paymentFor == '2') {
            // Tampilkan section daily salaries
        } elseif ($paymentFor == '3') {
            // Tampilkan section invoice purchases
        }
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([

                    Radio::make('payment_for')
                        ->options([
                            '1' => 'fuel service',
                            '2' => 'daily salary',
                            '3' => 'invoice',
                        ])
                        ->inline()
                        ->reactive()
                        ->afterStateUpdated(function ($state, $set) {
                            if ($state == '1') {
                                $items = FuelService::where('status', '1')->where('payment_type_id', '1')->get()->map(function ($item) {
                                    return ['fuel_service_id' => $item->id];
                                })->toArray();
                                self::updateInstanceStatusAndCalculateTotalAmount($items, $set, FuelService::class, 'fuel_service_id');
                            } elseif ($state == '2') {
                                $items = DailySalary::where('status', '3')->where('payment_type_id', '1')->get()->map(function ($item) {
                                    return ['daily_salary_id' => $item->id];
                                })->toArray();
                                self::updateInstanceStatusAndCalculateTotalAmount($items, $set, DailySalary::class, 'daily_salary_id');
                            } elseif ($state == '3') {
                                $items = InvoicePurchase::all()->map(function ($item) {
                                    return ['invoice_purchase_id' => $item->id];
                                })->toArray();
                                self::updateInstanceStatusAndCalculateTotalAmount($items, $set, InvoicePurchase::class, 'invoice_purchase_id');
                            }
                        }),

                    Select::make('supplier_id')
                        ->relationship('supplier', 'name')
                        ->required()

                ]),
            ]),

            Section::make()->schema([
                self::getInvoicePurchasesRepeater()
                ])
                ->visible(fn ($get) => $get('payment_for') == '3')
                ->hidden(fn ($operation) => $operation === 'edit' || $operation === 'view'),

            Section::make()->schema([
                self::getDailySalariesRepeater()
                ])
                ->visible(fn ($get) => $get('payment_for') == '2')
                ->hidden(fn ($operation) => $operation === 'edit' || $operation === 'view'),

            Section::make()->schema([
                self::getFuelServicesRepeater()
                ])
                ->visible(fn ($get) => $get('payment_for') == '1')
                ->hidden(fn ($operation) => $operation === 'edit' || $operation === 'view'),

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
                        ->numeric()
                        ->placeholder('Transfer Amount'),

                    ImageInput::make('image_adjust')
                        ->hidden(fn ($operation) => $operation === 'create'),

                    Notes::make('notes')
                        ->hidden(fn ($operation) => $operation === 'create'),

                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                ImageColumn::make('image')->visibility('public'),

                ImageColumn::make('image_adjust')->visibility('public'),

                TextColumn::make('supplier.name'),

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

    public static function getInvoicePurchasesRepeater(): Repeater
    {
        $invoicePurchases = InvoicePurchase::all()->map(function ($item) {
            return [
                'invoice_purchase_id' => $item->id,
                'amount' => $item->amount,
            ];
        });

        return TableRepeater::make('invoicePurchases')
            ->label('')
            ->default($invoicePurchases)
            ->relationship('invoicePurchases')
            ->schema([
                Select::make('invoice_purchase_id')
                    ->native(false)
                    ->options(InvoicePurchase::pluck('date', 'id')),
                TextInput::make('amount'),
            ]);
    }

    public static function getDailySalariesRepeater(): Repeater // OK
    {
        $dailySalaries = DailySalary::where('status', '3')->where('payment_type_id', '1')
            ->get()
            ->map(function ($item) {
                return [
                    'daily_salary_id' => $item->id,
                ];
            })->toArray();

        $options = DailySalary::where('status', '3')
            ->where('payment_type_id', '1')
            ->get()
            ->mapWithKeys(function ($dailySalary) {
                return [$dailySalary->id => $dailySalary->daily_salary_name];
            })->all();

        return TableRepeater::make('dailySalaryPaymentReceipts')
            ->hiddenLabel()
            ->default($dailySalaries)
            ->relationship()
            ->schema([
                Select::make('daily_salary_id')
                    ->label('Daily Salary')
                    ->native(false)
                    ->required()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->options($options),
            ])
            ->afterStateUpdated(function ($state, $set) {
                self::updateInstanceStatusAndCalculateTotalAmount($state, $set, DailySalary::class, 'daily_salary_id');
            });
    }

    public static function getFuelServicesRepeater(): Repeater
    {
        $options = FuelService::where('status', '1')
            ->where('payment_type_id', '1')
            ->get()
            ->mapWithKeys(function ($fuelService) {
                return [$fuelService->id => $fuelService->fuel_service_name];
            })->all();

        return TableRepeater::make('fuelServicePaymentReceipts')
            ->hiddenLabel()
            ->relationship()
            ->schema([
                Select::make('fuel_service_id')
                    ->label('Fuel Service')
                    ->native(false)
                    ->required()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->options($options),
            ])
            ->afterStateUpdated(function ($state, $set) {
                self::updateInstanceStatusAndCalculateTotalAmount($state, $set, FuelService::class, 'fuel_service_id');
            });
    }

    public static function calculateDifference($record)
    {
        return $record->total_amount - $record->transfer_amount;
    }


    protected static function calculateTotalAmount($state, $model, $field)
    {
        $totalAmount = 0;
        foreach ($state as $item) {
            $instance = $model::find($item[$field]);
            if ($instance) {
                $totalAmount += $instance->amount;
                // Update the status of the instance to 2
                $instance->status = 2;
                $instance->save();
            }
        }
        return $totalAmount;
    }

    protected static function updateInstanceStatusAndCalculateTotalAmount($items, $set, $model, $field)
    {
        $totalAmount = 0;
        foreach ($items as $item) {
            $instance = $model::find($item[$field]);
            if ($instance) {
                $totalAmount += $instance->amount;
                // Update the status of the instance to 2
                $instance->status = 2;
                $instance->save();
            }
        }
        $set('total_amount', $totalAmount);
    }
}
