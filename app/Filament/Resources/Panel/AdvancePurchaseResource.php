<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Purchases;
use App\Filament\Forms\CurrencyInput;
use App\Filament\Forms\CurrencyRepeaterInput;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use App\Filament\Forms\StoreSelect;
use App\Filament\Forms\SupplierSelect;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Table;
use App\Models\AdvancePurchase;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\AdvancePurchaseResource\Pages;
use App\Filament\Tables\AdvancePurchaseTable;
use App\Models\CashAdvance;
use Filament\Forms\Components\Repeater;
use App\Models\Product;
use App\Models\Supplier;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class AdvancePurchaseResource extends Resource
{
    protected static ?string $model = AdvancePurchase::class;

    // protected static ?string $navigationIcon = 'heroicon-s-shopping-cart';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Purchases::class;

    protected static ?string $navigationGroup = 'Advances';

    public static function getModelLabel(): string
    {
        return __('crud.advancePurchases.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.advancePurchases.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.advancePurchases.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->schema(static::getDetailsFormHeadSchema())
                ->columns(2),

            Section::make('Detail Pembelian')->schema([
                self::getItemsRepeater(),
            ]),

            Section::make()
                ->schema(static::getDetailsFormBottomSchema())
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        $advancePurchases = AdvancePurchase::query();

        if (!Auth::user()->hasRole('admin')) {
            $advancePurchases->where('user_id', Auth::id());
        }

        return $table
            ->query($advancePurchases)
            ->poll('60s')
            ->columns(AdvancePurchaseTable::schema())
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
            ->defaultSort('date', 'desc');
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
            'index' => Pages\ListAdvancePurchases::route('/'),
            'create' => Pages\CreateAdvancePurchase::route('/create'),
            'view' => Pages\ViewAdvancePurchase::route('/{record}'),
            'edit' => Pages\EditAdvancePurchase::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormHeadSchema(): array
    {
        return [
            ImageInput::make('image')
                ->directory('images/AdvancePurchase'),

            SupplierSelect::make('supplier_id'),

            Select::make('cash_advance_id')
                    ->required(fn () => Auth::user()->hasRole('staff'))
                    ->disabled(fn () => Auth::user()->hasRole('admin'))
                    ->label('Cash Advance')
                    ->inlineLabel()
                    ->relationship(
                        name: 'cashAdvance',
                        modifyQueryUsing: fn (Builder $query) =>
                            Auth::user()->hasRole('staff')
                                ? $query->where('user_id', Auth::id())->where('status', 1)
                                : $query,
                    )
                    ->getOptionLabelFromRecordUsing(fn (CashAdvance $record) => "{$record->cash_advance_name}"),

            StoreSelect::make('store_id'),

            DateInput::make('date'),

            Select::make('status')
                ->required()
                ->inlineLabel()
                ->required(fn () => Auth::user()->hasRole('admin'))
                ->hidden(fn ($operation) => $operation === 'create')
                ->disabled(fn () => Auth::user()->hasRole('staff'))
                ->preload()
                ->options([
                    '1' => 'belum diperiksa',
                    '2' => 'valid',
                    '3' => 'diperbaiki',
                    '4' => 'periksa ulang',
                ]),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('detailAdvancePurchases')
            ->relationship()
            ->schema([
                Select::make('product_id')
                    // ->label('Product')
                    ->hiddenLabel()
                    ->placeholder('Product')
                    ->searchable()
                    ->options(Product::query()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 4,
                    ])
                    ->searchable(),

                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->hiddenlabel()
                    ->placeholder('quantity')
                    ->default(1)
                    ->minValue(1)
                    ->required()
                    ->suffix(function (Get $get) {
                        $product = Product::find($get('product_id'));
                        return $product ? $product->unit->unit : '';
                    })
                    ->debounce(2000)
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateUnitPrice($get, $set);
                    }),

                CurrencyRepeaterInput::make('price')
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->debounce(2000)
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateUnitPrice($get, $set);
                        self::updateTotalPrice($get, $set);
                    }),

                CurrencyRepeaterInput::make('unit_price')
                    ->label('Unit Price')
                    ->readOnly()
                    ->columnSpan([
                        'md' => 2,
                    ]),
            ])
            ->columns([
                'md' => 10,
            ])
            ->afterStateUpdated(function (Get $get, Set $set) {
                self::updateTotalPrice($get, $set);
            });
    }

    public static function getDetailsFormBottomSchema(): array
    {
        return [
            Section::make()->schema([
                CurrencyInput::make('subtotal_price')
                    ->readOnly(),

                CurrencyInput::make('discount_price')
                    ->debounce(2000)
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set, $get) => $set('total_price', $get('subtotal_price') - $state)),

                CurrencyInput::make('total_price')
                    ->readOnly()
                    ->reactive(),

                Notes::make('notes'),
            ]),
        ];
    }

    protected static function updateUnitPrice(Get $get, Set $set): void
    {
        // Mengambil nilai dan mengonversi ke float, dengan default 0 untuk price dan 1 untuk quantity
        $price = $get('price') !== null ? (int) $get('price') : 1;
        $quantity = $get('quantity') !== null ? (int) $get('quantity') : 1;

        // Cek jika quantity 0 untuk menghindari pembagian dengan 0
         $unitPrice = $quantity > 0 ? $price / $quantity : 0;

        // $unitPrice = $price / $quantity;
        $set('unit_price', number_format($unitPrice, 0, ',', ''));
    }

    protected static function updateTotalPrice(Get $get, Set $set): void
    {
        // Get the repeater items or initialize to an empty array if null
        $repeaterItems = $get('detailAdvancePurchases') ?? [];

        $subtotalPrice = 0;

        foreach ($repeaterItems as $item) {
            if (isset($item['price'])) {
                $subtotalPrice += (int) $item['price'];
            }
        }

        $discountPrice = $get('discount_price') !== null ? (int) $get('discount_price') : 0;
        $totalPrice = $subtotalPrice - $discountPrice;

        $set('subtotal_price', $subtotalPrice);
        $set('total_price', $totalPrice);
    }
}

