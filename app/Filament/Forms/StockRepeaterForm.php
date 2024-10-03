<?php

namespace App\Filament\Forms;

use App\Models\Product;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class StockRepeaterForm
{
    public static function getRemainingRepeater(): Repeater
    {
        $products = Product::where('remaining', '1')->orderBy('name', 'asc')->get()->map(function ($item) {
            return [
                'product_id' => $item->id,
                'quantity' => $item->quantity,
            ];
        })->toArray();

        return Repeater::make('detailStockCards')
            ->hiddenLabel()
            ->default($products)
            ->relationship()
            ->addable(false)
            ->deletable(false)
            ->schema([
                Grid::make(['default' => 2])->schema([
                    BaseRepeaterSelect::make('product_id')
                        ->relationship('product', 'name', fn (Builder $query) => $query->where('remaining','1')),

                    NominalRepeaterInput::make('quantity')
                        ->suffix(function ($get) {
                            $product = Product::find($get('product_id'));
                            return $product ? $product->unit->unit : '';
                        }),
                ])
            ]);
    }

    public static function getStorageRepeater(): Repeater
    {
        $products = Product::where('request', '1')->orderBy('name', 'asc')->get()->map(function ($item) {
            return [
                'product_id' => $item->id,
                'quantity' => $item->quantity,
            ];
        })->toArray();

        return Repeater::make('detailStockCards')
            ->hiddenLabel()
            ->default($products)
            ->relationship()
            ->addable(false)
            ->deletable(false)
            ->schema([
                Grid::make(['default' => 2])->schema([
                    BaseRepeaterSelect::make('product_id')
                        ->relationship('product', 'name', fn (Builder $query) => $query->where('request','1')),

                    NominalRepeaterInput::make('quantity')
                        ->suffix(function ($get) {
                            $product = Product::find($get('product_id'));
                            return $product ? $product->unit->unit : '';
                        }),
                ])
            ]);
    }
}
