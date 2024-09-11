<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Advances;
use App\Filament\Clusters\Purchases;
use App\Filament\Columns\StatusColumn;
use App\Filament\Forms\DateInput;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\AdvancePurchaseResource\Pages;
use App\Filament\Tables\AdvancePurchaseTable;
use App\Models\CashAdvance;
use Filament\Forms\Components\Repeater;
use App\Models\Product;
use App\Models\Store;
use App\Models\Supplier;
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            FileUpload::make('image')
                ->rules(['image'])
                ->nullable()
                ->maxSize(1024)
                ->image()
                ->imageEditor()
                ->columnSpan([
                    'full'
                ])
                ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

            Select::make('cash_advance_id')
                    ->required(fn () => Auth::user()->hasRole('staff'))
                    ->disabled(fn () => Auth::user()->hasRole('admin'))
                    ->label('Cash Advance')
                    ->relationship(
                        name: 'cashAdvance',
                        modifyQueryUsing: fn (Builder $query) =>
                            Auth::user()->hasRole('staff')
                                ? $query->where('user_id', Auth::id())->where('status', 1)
                                : $query,
                    )
                    ->getOptionLabelFromRecordUsing(fn (CashAdvance $record) => "{$record->cash_advance_name}"),

            Select::make('store_id')
                ->required()
                ->relationship('store', 'nickname')
                ->searchable()
                ->preload()
                ->native(false),

            Select::make('supplier_id')
                ->label('Supplier')
                ->required()
                ->options(Supplier::all()->where('status', '<>', 3)->pluck('supplier_name', 'id'))
                ->searchable()
                ->preload()
                ->native(false),

            DateInput::make('date'),

            Select::make('status')
                ->required()
                ->required(fn () => Auth::user()->hasRole('admin'))
                ->hidden(fn ($operation) => $operation === 'create')
                ->disabled(fn () => Auth::user()->hasRole('staff'))
                ->preload()
                ->native(false)
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
                    ->label('Product')
                    ->searchable()
                    ->options(Product::query()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 4,
                    ])
                    ->searchable(),

                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->required()
                    ->suffix(function (Get $get) {
                        $product = Product::find($get('product_id'));
                        return $product ? $product->unit->unit : '';
                    })
                    ->debounce(500)
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateUnitPrice($get, $set);
                    }),

                TextInput::make('price')
                    ->required()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->debounce(500)
                    ->numeric()
                    ->reactive()
                    ->distinct()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateUnitPrice($get, $set);
                        self::updateTotalPrice($get, $set);
                    }),

                TextInput::make('unit_price')
                    ->label('Unit Price')
                    ->readOnly()
                    ->integer()
                    ->prefix('Rp')
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
                TextInput::make('subtotal_price')
                    ->readOnly()
                    ->default(0)
                    ->numeric()
                    ->prefix('Rp'),

                TextInput::make('discount_price')
                    ->required()
                    ->numeric()
                    ->debounce(1000)
                    ->default(0)
                    ->reactive()
                    ->prefix('Rp')
                   ->afterStateUpdated(fn ($state, callable $set, $get) => $set('total_price', $get('subtotal_price') - $state)),

                TextInput::make('total_price')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->prefix('Rp')
                    ->readOnly()
                    ->reactive(),

                RichEditor::make('notes')
                    ->nullable()
                    ->string()
                    ->fileAttachmentsVisibility('public'),
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

