<?php

namespace App\Filament\Forms;

use App\Models\Product;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class TransferRepeaterForm
{
    public static function getStoreRepeater(): Repeater
    {
        $products = Product::where('remaining', '1')->orderBy('name', 'asc')->get()->map(function ($item) {
            return [
                'product_id' => $item->id,
                'quantity' => $item->quantity,
            ];
        })->toArray();

        return Repeater::make('detailTransferCards')
            ->hiddenLabel()
            ->default($products)
            ->relationship()
            ->addable(false)
            ->deletable(false)
            ->columns(12)
            ->schema([
                \Filament\Forms\Components\Hidden::make('product_id'),

                \Filament\Forms\Components\Placeholder::make('product_name')
                    ->label('Produk')
                    ->hiddenLabel()
                    ->content(fn($get) => Product::find($get('product_id'))?->name)
                    ->extraAttributes(['class' => 'pt-2 font-medium text-gray-700 dark:text-gray-200'])
                    ->columnSpan(8),

                NominalRepeaterInput::make('quantity')
                    ->label('Jumlah')
                    ->placeholder('0')
                    ->suffix(function ($get) {
                        $product = Product::find($get('product_id'));
                        return $product ? $product->unit->unit : '';
                    })
                    ->columnSpan(4),
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

        return Repeater::make('detailTransferCards')
            ->hiddenLabel()
            ->default($products)
            ->relationship()
            ->addable(false)
            ->deletable(false)
            ->columns(12)
            ->schema([
                \Filament\Forms\Components\Hidden::make('product_id'),

                \Filament\Forms\Components\Placeholder::make('product_name')
                    ->label('Produk')
                    ->hiddenLabel()
                    ->content(fn($get) => Product::find($get('product_id'))?->name)
                    ->extraAttributes(['class' => 'pt-2 font-medium text-gray-700 dark:text-gray-200'])
                    ->columnSpan(8),

                NominalRepeaterInput::make('quantity')
                    ->label('Jumlah')
                    ->placeholder('0')
                    ->suffix(function ($get) {
                        $product = Product::find($get('product_id'));
                        return $product ? $product->unit->unit : '';
                    })
                    ->columnSpan(4),
            ]);
    }
}
