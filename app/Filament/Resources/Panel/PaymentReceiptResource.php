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
                    ImageInput::make('image'),

                    Radio::make('payment_for')
                        // ->placeholder('Payment for')
                        ->options([
                            '1' => 'fuel service',
                            '2' => 'daily salary',
                            '3' => 'invoice',
                        ])
                        ->inline()
                        // ->default(3)
                        ->hiddenLabel()
                        // ->inlineLabel(false)
                        ->reactive(),
                        // ->afterStateUpdated(function (Get $get, Set $set) {
                        //     $resource = new PaymentReceiptResource();
                        //     $resource->calculateTotalAmount($get, $set);
                        // }),

                    TextInput::make('total_amount')
                        ->hiddenLabel()
                        // ->inline()
                        // ->readOnly()
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

                    ImageInput::make('image_adjust'),

                    Notes::make('notes'),

                ]),
            ]),

            Section::make()->schema([
                self::getInvoicePurchasesRepeater()
            ])->visible(fn ($get) => $get('payment_for') == '3'),

            Section::make()->schema([
                self::getDailySalariesRepeater()
            ])->visible(fn ($get) => $get('payment_for') == '2'),

            Section::make()->schema([
                self::getFuelServicesRepeater()
            ])->visible(fn ($get) => $get('payment_for') == '1'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                ImageColumn::make('image')->visibility('public'),

                ImageColumn::make('image_adjust')->visibility('public'),

                TextColumn::make('payment_for')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'fuel service',
                            '2' => 'daily salary',
                            '3' => 'invoice purchase',
                    }),

               TextColumn::make('dailySalaryPaymentReceipts')
                    ->label('Detail Payments')
                    ->html()
                    ->formatStateUsing(function (PaymentReceipt $record) {
                        if ($record->payment_for == 2) {
                            $dailySalaryPaymentReceipts = $record->dailySalaries()->get();
                            return implode('<br>', $dailySalaryPaymentReceipts->map(function ($item) {
                                $amount = number_format($item->amount, 0, ',', '.');
                                return "{$item->createdBy->name} | {$item->date} | {$item->store->nickname} | Rp {$amount}";
                            })->toArray());
                        } elseif ($record->payment_for == 3) {
                            $invoicePurchasePaymentReceipts = $record->invoicePurchases()->get();
                            return implode('<br>', $invoicePurchasePaymentReceipts->map(function ($item) {
                                $amount = number_format($item->amount, 0, ',', '.');
                                return "{$item->vehicle->license_plate} | {$item->date} | {$item->supplier->name} | Rp {$amount}";
                            })->toArray());
                        }elseif ($record->payment_for == 1) {
                            $fuelServicePaymentReceipts = $record->fuelServices()->get();
                            return implode('<br>', $fuelServicePaymentReceipts->map(function ($item) {
                                $amount = number_format($item->amount, 0, ',', '.');
                                return "{$item->store->nickname} | {$item->date} | {$item->supplier->name} | Rp {$amount}";
                            })->toArray());
                        }
                    })
                    ->extraAttributes(['class' => 'whitespace-pre-wrap']),

                CurrencyColumn::make('total_amount'),

                CurrencyColumn::make('transfer_amount'),

            ])

            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => self::getDifference($record) !== 0),
                Tables\Actions\ViewAction::make()
                    ->visible(fn ($record) => self::getDifference($record) === 0),
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
            // RelationManagers\FuelServicesRelationManager::class,
            // RelationManagers\DailySalariesRelationManager::class,
            // RelationManagers\InvoicePurchasesRelationManager::class,
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
                $totalAmount = 0;
                foreach ($state as $item) {
                    $dailySalary = DailySalary::find($item['daily_salary_id']);
                    if ($dailySalary) {
                        $totalAmount += $dailySalary->amount;
                        // Update the status of the DailySalary to 2
                        $dailySalary->status = 2;
                        $dailySalary->save();
                    }
                }
                $set('total_amount', $totalAmount);
            });
            // ->afterStateUpdated(function (Get $get, Set $set) {
            //     self::calculateTotalAmount($get, $set);
            // });

    }

    public static function getFuelServicesRepeater(): Repeater
    {
        $fuelServices = FuelService::all()
            ->map(function ($item) {
                return [
                    'fuel_service_id' => $item->id,
                    'amount' => $item->amount,
                ];
            })->toArray();

        return Repeater::make('fuelServices')
            ->label('')
            ->default($fuelServices)
            ->relationship('fuelServices')
            ->schema([
                Select::make('fuel_service_id')
                    ->native(false)
                    ->options(FuelService::pluck('fuel_service', 'id')),
                TextInput::make('amount')
            ]);
    }

    protected static function calculateTotalAmount($state, $set)
    {
        $totalAmount = 0;
        foreach ($state as $item) {
            $dailySalary = DailySalary::find($item['daily_salary_id']);
            if ($dailySalary) {
                $totalAmount += $dailySalary->amount;
            }
        }
        $set('total_amount', $totalAmount);
    }

    public static function getDifference($record)
    {
        return $record->total_amount - $record->transfer_amount;
    }
    }
