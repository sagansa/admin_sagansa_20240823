<?php

namespace App\Filament\Forms;

use App\Models\Product;
use App\Models\SalesOrderDirect;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;

class SalesProductForm
{
    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('detailSalesOrders')
            ->label('')
            ->minItems(1)
            ->relationship()
            ->schema([

                Select::make('product_id')
                    ->placeholder('Product')
                    ->hiddenLabel()
                    ->searchable()
                    ->options(Product::query()
                        ->whereNotIn('online_category_id', [4])
                        ->pluck('name', 'id')
                        ->map(function ($name, $id) {
                            $product = Product::find($id);
                            return $product->name;
                    }))
                    ->required()
                    ->reactive()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 4,
                    ])
                    ->searchable(),

                TextInput::make('quantity')
                    ->hiddenLabel()
                    ->placeholder('Quantity')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->required()
                    ->debounce(2000)
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->reactive()
                    ->suffix(function (Get $get) {
                        $product = Product::find($get('product_id'));
                        return $product ? $product->unit->unit : '';
                    })
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateSubtotalPrice($get, $set);
                        self::updateTotalPrice($get, $set);
                    }),

                CurrencyRepeaterInput::make('unit_price')
                    ->placeholder('Unit Price')
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateSubtotalPrice($get, $set);
                        self::updateTotalPrice($get, $set);
                    }),

                CurrencyRepeaterInput::make('subtotal_price')
                    ->placeholder('Subtotal Price')
                    ->readOnly()
                    ->columnSpan([
                        'md' => 2,
                    ])

                ])
                ->columns([
                'md' => 10,
            ])
            ->afterStateUpdated(function (Get $get, Set $set) {
                self::updateTotalPrice($get, $set);
            });
            // ->deletable(fn ($record) => (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin')));
    }

    protected static function updateSubtotalPrice(Get $get, Set $set): void
    {
        // Mengambil nilai dan mengonversi ke float, dengan default 0 untuk price dan 1 untuk quantity
        $unitPrice = $get('unit_price');
        $quantity = $get('quantity');

        // Cek jika quantity atau unit price null, maka tidak melakukan perhitungan
        if ($quantity === null || $unitPrice === null) {
            $set('subtotal_price', '');
            return;
        }
        // // Mengambil nilai dan mengonversi ke float, dengan default 0 untuk price dan 1 untuk quantity
        // $unitPrice = $get('unit_price') !== null ? (int) $get('unit_price') : 0;
        // $quantity = $get('quantity') !== null ? (int) $get('quantity') : 1;

        // Cek jika quantity 0 untuk menghindari pembagian dengan 0
        $subtotalPrice = $quantity > 0 ? (int) $unitPrice * (int) $quantity : 0;

        // $unitPrice = $price / $quantity;
        $set('subtotal_price', number_format($subtotalPrice, 0, ',', ''));
    }

    protected static function updateTotalPrice(Get $get, Set $set): void
    {
        // Get the repeater items or initialize to an empty array if null
        $repeaterItems = $get('detailSalesOrders') ?? [];

        $subTotalPrice = 0;
        $totalPrice = 0;
        $shippingCost = $get('shipping_cost') !== null ? (int) $get('shipping_cost') : 0;

        foreach ($repeaterItems as $item) {
            $quantity = $item['quantity'];
            $unitPrice = $item['unit_price'];

            // Cek jika quantity atau unit price null, maka tidak melakukan perhitungan
            if ($quantity === null || $unitPrice === null) {
                continue;
            }

            // Cek jika quantity null sebelum melakukan operasi perkalian
            $subTotalPrice += (int) $quantity * (int) $unitPrice;
        }

        $totalPrice = $subTotalPrice + $shippingCost;

        $set('total_price', number_format($totalPrice, 0, ',', ''));
    }
}
