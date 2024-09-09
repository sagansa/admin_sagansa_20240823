<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Invoices;
use App\Filament\Clusters\Purchases;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\InvoicePurchase;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\InvoicePurchaseResource\Pages;
use App\Filament\Tables\InvoicePurchaseTable;
use App\Models\DetailRequest;
use App\Models\PaymentType;
use App\Models\Store;
use App\Models\Supplier;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;

class InvoicePurchaseResource extends Resource
{
    protected static ?string $model = InvoicePurchase::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Invoice';

    protected static ?string $pluralLabel = 'Invoices';

    protected static ?string $cluster = Purchases::class;

    public static function getModelLabel(): string
    {
        return __('crud.invoicePurchases.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.invoicePurchases.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.invoicePurchases.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 2])->schema(
                    static::getDetailsFormHeadSchema()),
            ]),

            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    static::getItemsRepeater()
                ])
            ]),

            Section::make()->schema([
                Grid::make(['default' => 1])->schema(
                    static::getDetailsFormBottomSchema()),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $invoicePurchases = InvoicePurchase::query();

        if (Auth::user()->hasRole('staff')) {
            $invoicePurchases->where('created_by_id', Auth::id());
        }

        return $table
            ->query($invoicePurchases)
            ->poll('60s')
            ->columns(
                InvoicePurchaseTable::schema()
            )
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
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoicePurchases::route('/'),
            'create' => Pages\CreateInvoicePurchase::route('/create'),
            'view' => Pages\ViewInvoicePurchase::route('/{record}'),
            'edit' => Pages\EditInvoicePurchase::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormHeadSchema(): array
    {


        return [
            ImageInput::make('image'),

            Select::make('payment_type_id')
                ->required()
                ->reactive()
                ->relationship(
                    name: 'paymentType',
                    modifyQueryUsing: fn (Builder $query) => $query->where('status', '1'),
                )

                ->getOptionLabelFromRecordUsing(fn (PaymentType $record) => "{$record->name}")
                ->default(2)
                ->disableOptionWhen(fn (string $value): bool => $value === '2')
                ->in(fn (Select $component): array => array_keys($component->getEnabledOptions()))
                ->preload()
                ->native(false)
                ->afterStateUpdated(function ($state, callable $set) {
                    // $set('detailInvoices', null);
                    // $set('store_id', null);
                }),

            Select::make('store_id')
                ->required()
                ->reactive()
                ->relationship(
                    name: 'store',
                    modifyQueryUsing: fn (Builder $query) => $query->where('status','<>', '8'),
                )
                ->getOptionLabelFromRecordUsing(fn (Store $record) => "{$record->nickname}")
                ->searchable()
                ->preload()
                ->native(false)
                ->afterStateUpdated(function ($state, callable $set) {
                    // $set('detailInvoices', null);
                    // $set('payment_type_id', null);
                }),

            Select::make('supplier_id')
                ->required()
                ->relationship(
                    name: 'supplier',
                    modifyQueryUsing: fn (Builder $query) => $query->where('status','<>', '3'),
                )
                ->getOptionLabelFromRecordUsing(fn (Supplier $record) => "{$record->supplier_name}")
                ->searchable()
                ->preload()
                ->native(false),

            DateInput::make('date'),

            // Placeholder::make('payment_status'),
                // ->hidden(fn ($operation) => $operation === 'create'),
                // ->content(fn (DetailRequest $record): string => [
                //     '1' => __('belum dibayar'),
                //     '2' => __('sudah dibayar'),
                //     '3' => __('tidak valid'),
                // ][$record->payment_status])
                // ->columnSpan(2),

            Select::make('payment_status')
                ->required(fn () => Auth::user()->hasRole('admin'))
                ->disabled(fn () => !Auth::user()->hasRole('admin'))
                ->hidden(fn ($operation) => $operation === 'create')
                ->preload()
                ->options([
                    '1' => 'belum dibayar',
                    '2' => 'sudah dibayar',
                    '3' => 'tidak valid',
                ])
                ->native(false),

            Select::make('order_status')
                ->required()
                ->hidden(fn ($operation) => $operation === 'create')
                ->preload()
                ->options([
                    '1' => 'belum diterima',
                    '2' => 'sudah diterima',
                    '3' => 'dikembalikan',
                ])
                ->native(false),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('detailInvoices')
            ->hiddenLabel()
            ->minItems(1)
            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, InvoicePurchase $record): array {
                $data['status'] = '3';

                return $data;
            })
            ->columns(['md' => 8])
            ->relationship()
            ->schema([
                Select::make('detail_request_id')
                    ->label('Detail Request')

                    ->relationship(
                        name: 'detailRequest',
                        modifyQueryUsing: function (Builder $query, callable $get) {
                            $paymentTypeId = $get('../../payment_type_id');
                            $storeId = $get('../../store_id');

                            $statusFilter = '';
                                if ($paymentTypeId == 1) { // transfer
                                    $statusFilter = '1'; // process dan approved
                                } elseif ($paymentTypeId == 2) { // tunai
                                    $statusFilter = '4'; // approved
                            }

                            $queryFinal = $query
                                ->where('store_id', $storeId)
                                ->where('status', $statusFilter)
                                ->orderBy('id', 'desc'); // tambahkan metode orderBy;

                            return $queryFinal;
                        }
                    )

                    // ->relationship(
                    //     name: 'detailRequest',
                    //     modifyQueryUsing: function (Builder $query, callable $get) {
                    //         $paymentTypeId = $get('../../payment_type_id'); // Access payment_type_id from the parent form
                    //         $storeId = $get('../../store_id'); // Access store_id from the parent form

                    //         // Determine the status filter based on the payment type
                    //         $statusFilter = match ($paymentTypeId) {
                    //             1 => '1', // Process and approved for transfer payment type
                    //             2 => '4', // Approved for cash payment type
                    //             default => '',
                    //         };

                    //         // Modify the query with the filters applied
                    //         $query->when($storeId, function ($query) use ($storeId) {
                    //             return $query->where('store_id', $storeId);
                    //         })
                    //         ->when($statusFilter, function ($query) use ($statusFilter) {
                    //             return $query->where('status', $statusFilter);
                    //         })
                    //         ->orderBy('id', 'desc'); // Order the results by ID in descending order

                    //         return $query;
                    //     }
                    // )

                    ->getOptionLabelFromRecordUsing(fn (DetailRequest $record) => "{$record->detail_request_name}")
                    ->native(false)
                    ->required()
                    ->preload()
                    ->searchable()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan(['md' => 4]),

                TextInput::make('quantity_product')
                    ->required()
                    ->reactive()
                    ->minValue(1)
                    ->default(1)
                    ->suffix(function (Get $get) {
                        $detailRequest = DetailRequest::find($get('detail_request_id'));
                        return $detailRequest ? $detailRequest->product->unit->unit : '';
                    })
                    ->columnSpan(['md' => 2]),

                TextInput::make('subtotal_invoice')
                    ->required()
                    ->reactive()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->numeric()
                    // ->distinct()
                    ->debounce(500)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotalPrice($get, $set);
                    })

                    ->columnSpan(['md' => 2]),
            ])
            ->afterStateUpdated(function (Get $get, Set $set) {
                self::updateTotalPrice($get, $set);
            });
    }

    public static function getDetailsFormBottomSchema(): array
    {
        return[
            TextInput::make('taxes')
                ->required()
                ->minValue(0)
                ->reactive()
                ->numeric()
                ->debounce(500)
                ->default(0)
                ->afterStateUpdated(function (Get $get, Set $set) {
                    self::updateTotalPrice($get, $set);
                }),

            TextInput::make('discounts')
                ->required()
                ->minValue(0)
                ->reactive()
                ->numeric()
                ->debounce(500)
                ->default(0)
                ->afterStateUpdated(function (Get $get, Set $set) {
                    self::updateTotalPrice($get, $set);
                }),

            TextInput::make('total_price')
                ->readOnly()
                ->minValue(0),

            Notes::make('notes'),
        ];
    }

    protected static function updateTotalPrice(Get $get, Set $set): void
    {
        // Get the repeater items or initialize to an empty array if null
        $repeaterItems = $get('detailInvoices') ?? [];

        $subtotalPrice = 0;
        $totalPrice = 0;
        $taxes = 0;
        $discounts = 0;

        $taxes = $get('taxes') !== null ? (int) $get('taxes') : 0;
        $discounts = $get('discounts') !== null ? (int) $get('discounts') : 0;

        foreach ($repeaterItems as $item) {
            if (isset($item['subtotal_invoice'])) {
                $subtotalPrice += (int) $item['subtotal_invoice'];
            }
        }

        $totalPrice = $subtotalPrice + $taxes - $discounts;

        // $set('subtotal_price', number_format($subtotalPrice, 0, ',', ''));
        // $set('total_price', number_format($totalPrice, 0, ',', ''));
        $set('subtotal_price', $subtotalPrice);
        $set('total_price', $totalPrice);
    }
}
