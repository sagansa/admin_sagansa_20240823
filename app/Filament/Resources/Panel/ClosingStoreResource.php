<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Closings;
use App\Filament\Clusters\Purchases;
use App\Filament\Clusters\Sales;
use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\StatusColumn;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use App\Filament\Forms\StoreSelect;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ClosingStore;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Panel\ClosingStoreResource\Pages;
use App\Filament\Resources\Panel\ClosingStoreResource\RelationManagers;
use App\Models\DailySalary;
use App\Models\FuelService;
use App\Models\InvoicePurchase;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountCashless;

class ClosingStoreResource extends Resource
{
    protected static ?string $model = ClosingStore::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Closing';

    protected static ?string $pluralLabel = 'Store';

    protected static ?string $cluster = Closings::class;

    public static function getModelLabel(): string
    {
        return __('crud.closingStores.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.closingStores.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.closingStores.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    StoreSelect::make('store_id'),

                    Select::make('shift_store_id')
                        ->required()
                        ->relationship('shiftStore', 'name')
                        ->preload()
                        ->native(false),

                    DatePicker::make('date')
                        ->rules(['date'])
                        ->default('today')
                        ->required()
                        ->native(false),

                    TextInput::make('cash_from_yesterday')
                        ->prefix('Rp')
                        ->required()
                        ->reactive()
                        ->debounce(2000)
                        ->minValue(0)
                        ->numeric()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    TextInput::make('cash_for_tomorrow')
                        ->prefix('Rp')
                        ->required()
                        ->reactive()
                        ->debounce(2000)
                        ->minValue(0)
                        ->numeric()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    TextInput::make('total_cash_transfer')
                        ->prefix('Rp')
                        ->required()
                        ->reactive()
                        ->debounce(2000)
                        ->minValue(0)
                        ->numeric()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),
                ]),
            ]),

            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('fuelServices')
                        ->multiple()
                        ->relationship(
                            name: 'fuelServices',
                            modifyQueryUsing: fn (Builder $query, $get) => $query
                                ->where('payment_type_id', '2')
                                ->where('status', '1')
                                ->whereDate('date', '>=', now()->subDays(10)) // add this line
                                ->orderBy('date', 'desc')
                        )
                        ->getOptionLabelFromRecordUsing(fn (FuelService $record) => "{$record->fuel_service_name}")
                        ->preload()
                        ->reactive()
                        ->native(false)
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                            self::updateFuelServiceStatus($get, $set);
                        }),

                    Select::make('dailySalaries')
                        ->multiple()
                        ->relationship(
                            name: 'dailySalaries',
                            modifyQueryUsing: fn (Builder $query, $get) => $query
                                ->where('payment_type_id', '2')
                                ->where('status', '1')
                                ->when($get('store_id'), fn ($query, $storeId) => $query->where('store_id', $storeId)) // Menggunakan store_id yang dipilih
                                ->whereDate('date', '>=', now()->subDays(10)) // add this line
                                ->orderBy('date', 'desc')
                        )
                        ->getOptionLabelFromRecordUsing(fn (DailySalary $record) => "{$record->daily_salary_name}")
                        ->preload()
                        ->reactive()
                        ->native(false)
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            self::updateDailySalaryStatus($state, $set);
                            self::updateTotalOmzet($get, $set);
                        }),

                    Select::make('invoicePurchases')
                        ->multiple()
                        ->relationship(
                            name: 'invoicePurchases',
                            modifyQueryUsing: fn (Builder $query, $get) => $query
                                ->where('payment_type_id', '2')
                                ->where('payment_status', '1')
                                ->when($get('store_id'), fn ($query, $storeId) => $query->where('store_id', $storeId)) // Menggunakan store_id yang dipilih
                                ->whereDate('date', '>=', now()->subDays(10)) // add this line
                                ->orderBy('date','desc')
                        )
                        ->getOptionLabelFromRecordUsing(fn (InvoicePurchase $record) => "{$record->invoice_purchase_name}")
                        ->preload()
                        ->reactive()
                        ->native(false)
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                            self::updateInvoicePurchaseStatus($get, $set);
                        }),

                ])
            ]),

            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Repeater::make('cashlesses')
                        ->relationship()
                        ->schema([
                            Select::make('account_cashless_id')
                                ->required()
                                ->native(false)
                                ->preload()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->relationship(
                                    name: 'accountCashless',
                                    modifyQueryUsing: function (Builder $query, callable $get) {
                                        $storeId = $get('../../store_id');
                                        $query->where('store_id', $storeId);

                                        return $query;
                                    }
                                )
                                ->getOptionLabelFromRecordUsing(fn (AccountCashless $record) => $record->account_cashless_name),

                            TextInput::make('bruto_apl')
                                ->label('Bruto Total Omzet')
                                ->prefix('Rp')
                                ->required()
                                ->numeric(),

                            ImageInput::make('image'),
                        ])

                ])
            ]),

            Section::make()->schema([
                Grid::make(['default' => 1])->schema([

                    TextInput::make('total_fuel_service')
                        ->prefix('Rp')
                        ->disabled()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    TextInput::make('total_daily_salary')
                        ->prefix('Rp')
                        ->disabled()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    TextInput::make('total_invoice_purchase')
                        ->prefix('Rp')
                        ->disabled()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    TextInput::make('spending_total_cash')
                        ->prefix('Rp')
                        ->disabled()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    TextInput::make('total_cash')
                        ->prefix('Rp')
                        ->disabled()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    TextInput::make('total_cashless')
                        ->prefix('Rp')
                        ->disabled()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    TextInput::make('total_omzet')
                        ->prefix('Rp')
                        ->disabled()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    Select::make('transfer_by_id')
                        ->nullable()
                        // ->required(function ($request) {
                        //     return $request->total_cash_transfer != 0;
                        // })
                        ->relationship('transferBy', 'name')
                        ->preload()
                        ->native(false),

                    Select::make('status')
                        ->required()
                        ->hidden(fn ($operation) => $operation === 'create')
                        ->disabled(fn () => Auth::user()->hasRole('staff'))
                        ->required(fn () => Auth::user()->hasRole('admin'))
                        ->preload()
                        ->native(false)
                        ->options([
                            '1' => 'belum diperiksa',
                            '2' => 'valid',
                            '3' => 'diperbaiki',
                            '4' => 'periksa ulang',
                        ]),

                    Notes::make('notes'),
                ])
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $query = ClosingStore::query();

        if (!Auth::user()->hasRole('admin')) {
            $query->where('created_by_id', Auth::id());
        }

        return $table
            ->query($query)
            ->poll('60s')
            ->columns([
                TextColumn::make('store.nickname'),

                TextColumn::make('shiftStore.name'),

                TextColumn::make('date'),

                CurrencyColumn::make('cash_from_yesterday'),

                CurrencyColumn::make('cash_for_tomorrow'),

                CurrencyColumn::make('total_cash_transfer'),

                TextColumn::make('createdBy.name')
                    ->hidden(fn () => !Auth::user()->hasRole('admin')),

                TextColumn::make('transferBy.name'),

                StatusColumn::make('status'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort(fn (Builder $query) => $query->orderBy('date', 'desc')->orderBy('created_at', 'desc'));
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CashlessesRelationManager::class,
            RelationManagers\InvoicePurchasesRelationManager::class,
            RelationManagers\DailySalariesRelationManager::class,
            RelationManagers\FuelServicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClosingStores::route('/'),
            'create' => Pages\CreateClosingStore::route('/create'),
            'view' => Pages\ViewClosingStore::route('/{record}'),
            'edit' => Pages\EditClosingStore::route('/{record}/edit'),
        ];
    }

    protected static function updateDailySalaryStatus($state, $set): void
    {
        foreach ($state as $dailySalaryId) {
            $dailySalary = DailySalary::find($dailySalaryId);
            if  ($dailySalary) {
                $dailySalary->status = 2;
                $dailySalary->save();
            }
        }
    }

    protected static function updateFuelServiceStatus($state, $set): void
    {
        foreach ($state as $fuelServiceId) {
            $fuelService = FuelService::find($fuelServiceId);
            if ($fuelService) {
                $fuelService->status = 2;
                $fuelService->save();
            }
        }
    }

    protected static function updateInvoicePurchaseStatus($state, $set): void
    {
        foreach ($state as $invoicePurchaseId) {
            $invoicePurchase = InvoicePurchase::find($invoicePurchaseId);
            if ($invoicePurchase) {
                $invoicePurchase->status = 2;
                $invoicePurchase->save();
            }
        }
    }

    protected static function updateTotalOmzet(Get $get, Set $set): void
    {
        $fuelServices = $get('fuelServices') ?? [];
        $dailySalaries = $get('dailySalaries') ?? [];
        $invoicePurchases = $get('invoicePurchases') ?? [];
        $cashlesses = $get('cashlesses') ?? [];

        // total fuel service
        $totalFuelService = 0;
        foreach ($fuelServices as $fuelServiceId) {
            $fuelService = FuelService::find($fuelServiceId);
            if ($fuelService) {
                $totalFuelService += $fuelService->amount;
            }
        }
        $set('total_fuel_service', $totalFuelService);

        // total daily salary
        $totalDailySalary = 0;
        foreach ($dailySalaries as $dailySalaryId) {
            $dailySalary = DailySalary::find($dailySalaryId);
            if ($dailySalary) {
                $totalDailySalary += $dailySalary->amount;
            }
        }
        $set('total_daily_salary', $totalDailySalary);

        // total invoice purchase
        $totalInvoicePurchase = 0;
        foreach ($invoicePurchases as $invoicePurchaseId) {
            $invoicePurchase = InvoicePurchase::find($invoicePurchaseId);
            if ($invoicePurchase) {
                $totalInvoicePurchase += $invoicePurchase->total_price;
            }
        }
        $set('total_invoice_purchase', $totalInvoicePurchase);

        // total cashless
        $totalCashless = 0;
        foreach ($cashlesses as $cashless) {
            if (isset($cashless['bruto_apl'])) {
                $totalCashless += (int) $cashless['bruto_apl'];
            }
        }
        $set('total_cashless', $totalCashless);

        $spendingTotalCash = $totalFuelService + $totalDailySalary + $totalInvoicePurchase;
        $set('spending_total_cash', $spendingTotalCash);

        $cashForTomorrow = $get('cash_for_tomorrow') ?? 0;
        $cashFromYesterday = $get('cash_from_yesterday') ?? 0;
        $totalCashTransfer = $get('total_cash_transfer') ?? 0;

        // total cash
        $totalCash = floatval($cashForTomorrow) - floatval($cashFromYesterday) + floatval($spendingTotalCash) + floatval($totalCashTransfer);
        $set('total_cash', $totalCash);

        // total omzet
        $totalOmzet = $totalCash + $totalCashless;
        $set('total_omzet', $totalOmzet);
    }

}

// spending_cash_total = fuelService + dailySalary + invoicePurchase
// penjumlahan cashless bruto_apl = cashless_total
// cash_total = cash_for_tomorrow - cash_from_yesterday + spending_cash_total + total_cash_transfer
// omzet = cash_total + cashless_total
