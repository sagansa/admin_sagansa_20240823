<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Invoices;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\InvoicePurchaseResource\Pages;
use App\Models\DetailRequest;
use App\Models\PaymentType;
use App\Models\Store;
use App\Models\Supplier;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Facades\Auth;

class InvoicePurchaseResource extends Resource
{
    protected static ?string $model = InvoicePurchase::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Purchase';

    protected static ?string $cluster = Invoices::class;

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
        return $table
            ->poll('60s')
            ->columns([
                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('paymentType.name'),

                TextColumn::make('store.nickname'),

                TextColumn::make('supplier.name'),

                TextColumn::make('date'),

                TextColumn::make('total_price'),

                TextColumn::make('payment_status'),

                TextColumn::make('order_status'),

                TextColumn::make('createdBy.name'),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\Action::make('transfer')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by_id'] = Auth::id();
                        $data['payment_status'] = '1';
                        $data['order_status'] = '1';
                        $data['payment_type_id'] = '1';

                        return $data;
                    })->size(ActionSize::Large),
                Tables\Actions\Action::make('tunai')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by_id'] = Auth::id();
                        $data['payment_status'] = '2';
                        $data['order_status'] = '1';
                        $data['payment_type_id'] = '1';

                        return $data;
                    })->size(ActionSize::Large)
            ])
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoicePurchases::route('/'),
            // 'create' => Pages\CreateInvoicePurchase::route('/create'),
            'view' => Pages\ViewInvoicePurchase::route('/{record}'),
            'edit' => Pages\EditInvoicePurchase::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormHeadSchema(): array
    {
        return [
            ImageInput::make('image'),

            // Select::make('payment_type_id')
            //     ->required()
            //     ->reactive()
            //     ->relationship(
            //         name: 'paymentType',
            //         modifyQueryUsing: fn (Builder $query) => $query->where('status', '1'),
            //     )
            //     ->getOptionLabelFromRecordUsing(fn (PaymentType $record) => "{$record->name}")
            //     ->preload()
            //     ->native(false),

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
                ->native(false),

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

            Placeholder::make('payment_status')
                ->hidden(fn ($operation) => $operation === 'create')
                ->content(fn (DetailRequest $record): string => [
                    '1' => __('belum dibayar'),
                    '2' => __('sudah dibayar'),
                    '3' => __('tidak valid'),
                ][$record->payment_status])
                ->columnSpan(2),

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
            ->columns(['md' => 8])
            ->relationship()
            ->schema([
                Select::make('detail_request_id')
                    ->relationship(
                        name: 'detailRequest',
                        modifyQueryUsing: fn (Builder $query) => $query,
                        // modifyQueryUsing: fn (Builder $query) => $query->whereHas('product', fn ($query) => $query->where('payment_type_id', 1)),
                    )
                    ->getOptionLabelFromRecordUsing(fn (DetailRequest $record) => "{$record->product->name}")
                    // ->relationship(
                    //     name: 'detailRequest',
                    //     modifyQueryUsing: fn (Builder $query) => $query->whereHas('product', fn ($query) => $query->where('payment_type_id', request()->input('payment_type_id')))
                    // ->whereHas('store', fn ($query) => $query->where('id', request()->input('store_id'))),
                    // )
                    // ->getOptionLabelFromRecordUsing(fn (DetailRequest $record) => $record->product->name)
                    ->native(false)
                    ->reactive()
                    ->required()
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan(['md' => 4]),

                TextInput::make('quantity_product')
                    ->required()
                    ->reactive()
                    ->minValue(1)
                    ->default(1)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateUnitPrice($get, $set);
                    })
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
                    ->distinct()
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
                ->default(0)
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

        // foreach ($repeaterItems as $item) {
        //     $subtotalInvoice = $item['subtotal_invoice'] ?? 0;

        //     $subtotalPrice += $subtotalInvoice;
        // }

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
