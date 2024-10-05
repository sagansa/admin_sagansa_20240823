<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Closings;
use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\StatusColumn;
use App\Filament\Filters\DateFilter;
use App\Filament\Filters\SelectStoreFilter;
use App\Filament\Forms\CurrencyInput;
use App\Filament\Forms\CurrencyRepeaterInput;
use App\Filament\Forms\DateInput;
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
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ColumnGroup;
use Illuminate\Database\Eloquent\Collection;

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
                Grid::make(['default' => 2])->schema([
                    StoreSelect::make('store_id')
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $set('cash_from_yesterday', ClosingStoreResource::getCashForTomorrow($state));
                            }
                        }),

                    Select::make('shift_store_id')
                        ->required()
                        ->inlineLabel()
                        ->relationship('shiftStore', 'name')
                        ->preload(),

                    DateInput::make('date'),

                    CurrencyInput::make('cash_from_yesterday')
                        ->debounce(2000)
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    CurrencyInput::make('cash_for_tomorrow')
                        ->debounce(2000)
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    CurrencyInput::make('total_cash_transfer')
                        ->debounce(2000)
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    Select::make('transfer_by_id')
                        ->nullable()
                        ->inlineLabel()
                        ->relationship('transferBy', 'name', fn(Builder $query) => $query
                            ->whereHas('roles', fn(Builder $query) => $query
                                ->where('name', 'staff') || $query
                                ->where('name', 'supervisor')))
                        ->preload()
                        ->visible(fn($get) => $get('total_cash_transfer') !== 0),
                ]),
            ]),

            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('fuelServices')
                        ->multiple()
                        ->inlineLabel()
                        ->relationship(
                            name: 'fuelServices',
                            modifyQueryUsing: fn(Builder $query, $get) => $query
                                ->where('payment_type_id', '2')
                                ->where('status', '1')
                                ->whereDate('date', '>=', now()->subDays(10)) // add this line
                                ->orderBy('date', 'desc')
                        )
                        ->getOptionLabelFromRecordUsing(fn(FuelService $record) => "{$record->fuel_service_name}")
                        ->preload()
                        ->reactive()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                            self::updateFuelServiceStatus($get, $set);
                        }),

                    Select::make('dailySalaries')
                        ->multiple()
                        ->inlineLabel()
                        ->relationship(
                            name: 'dailySalaries',
                            modifyQueryUsing: fn(Builder $query, $get) => $query
                                ->where('payment_type_id', '2')
                                ->where('status', '1')
                                ->when($get('store_id'), fn($query, $storeId) => $query->where('store_id', $storeId)) // Menggunakan store_id yang dipilih
                                ->whereDate('date', '>=', now()->subDays(15)) // add this line
                                ->orderBy('date', 'desc')
                        )
                        ->getOptionLabelFromRecordUsing(fn(DailySalary $record) => "{$record->daily_salary_name}")
                        ->preload()
                        ->reactive()
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            self::updateDailySalaryStatus($state, $set);
                            self::updateTotalOmzet($get, $set);
                        }),

                    Select::make('invoicePurchases')
                        ->multiple()
                        ->inlineLabel()
                        ->relationship(
                            name: 'invoicePurchases',
                            modifyQueryUsing: fn(Builder $query, $get) => $query
                                ->where('payment_type_id', '2')
                                ->where('payment_status', '1')
                                ->when($get('store_id'), fn($query, $storeId) => $query->where('store_id', $storeId)) // Menggunakan store_id yang dipilih
                                ->whereDate('date', '>=', now()->subDays(10)) // add this line
                                ->orderBy('date', 'desc')
                        )
                        ->getOptionLabelFromRecordUsing(fn(InvoicePurchase $record) => "{$record->invoice_purchase_name}")
                        ->preload()
                        ->reactive()
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
                                ->hiddenLabel()
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
                                ->getOptionLabelFromRecordUsing(fn(AccountCashless $record) => $record->account_cashless_name),

                            CurrencyRepeaterInput::make('bruto_apl')
                                ->placeholder('Bruto Total Omzet'),

                            ImageInput::make('image')
                                ->hiddenLabel()
                                ->directory('images/ClosingStore'),
                        ])

                ])
            ]),

            Section::make('TIDAK PERLU DIISI')->schema([
                Grid::make(['default' => 2])->schema([

                    CurrencyInput::make('total_fuel_service')
                        ->readOnly()
                        ->inlineLabel()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    CurrencyInput::make('total_daily_salary')
                        ->readOnly()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    CurrencyInput::make('total_invoice_purchase')
                        ->readOnly()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    CurrencyInput::make('spending_total_cash')
                        ->readOnly()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    CurrencyInput::make('total_cash')
                        ->readOnly()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    CurrencyInput::make('total_cashless')
                        ->readOnly()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    CurrencyInput::make('total_omzet')
                        ->readOnly()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotalOmzet($get, $set);
                        }),

                    Select::make('status')
                        ->required()
                        ->inlineLabel()
                        ->hidden(fn($operation) => $operation === 'create')
                        ->disabled(fn() => Auth::user()->hasRole('staff'))
                        ->required(fn() => Auth::user()->hasRole('admin'))
                        ->preload()
                        ->options([
                            '1' => 'belum diperiksa',
                            '2' => 'valid',
                            '3' => 'diperbaiki',
                            '4' => 'periksa ulang',
                        ]),
                ]),

                Grid::make(['default' => 1])->schema([
                    Notes::make('notes'),
                ])
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $query = ClosingStore::query();

        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('super_admin')) {
            $query->where('created_by_id', Auth::id());
        }

        return $table
            ->query($query)
            ->poll('60s')
            ->columns([
                TextColumn::make('store.nickname'),

                TextColumn::make('shiftStore.name')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('date')
                    ->sortable(),

                ColumnGroup::make('Cash', [
                    CurrencyColumn::make('cash_from_yesterday')
                        ->label('From Yesterday'),

                    CurrencyColumn::make('cash_for_tomorrow')
                        ->label('For Tomorrow'),

                    CurrencyColumn::make('total_cash_transfer')
                        ->label('Total Transfer'),
                ])->alignCenter(),

                TextColumn::make('createdBy.name')
                    ->hidden(fn() => !Auth::user()->hasRole('admin')),

                TextColumn::make('transferBy.name')
                    ->toggleable(isToggledHiddenByDefault: true),

                StatusColumn::make('status'),
            ])
            ->filters([
                SelectStoreFilter::make('store_id'),
                DateFilter::make('date'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('setStatusToDiperbaiki')
                        ->label('Set Status to Diperbaiki')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            ClosingStore::whereIn('id', $records->pluck('id'))->update(['status' => 3]);
                        })
                        ->color('warning'),
                ]),
            ])
            ->defaultSort(fn(Builder $query) => $query->orderBy('date', 'desc')->orderBy('created_at', 'desc'));
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
            if ($dailySalary) {
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
                $invoicePurchase->payment_status = 2;
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

    public static function getCashForTomorrow($storeId)
    {
        $cashFromYesterday = ClosingStore::where('store_id', $storeId)
            ->latest('created_at')
            ->first();

        return $cashFromYesterday ? $cashFromYesterday->cash_for_tomorrow : 0;
    }
}
