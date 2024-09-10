<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Stock;
use App\Filament\Columns\StatusColumn;
use App\Filament\Forms\BaseSelectInput;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\StatusSelect;
use App\Filament\Forms\StatusSelectInput;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RemainingStock;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Panel\RemainingStockResource\Pages;
use App\Filament\Resources\Panel\RemainingStockResource\RelationManagers;
use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;

class RemainingStockResource extends Resource
{
    protected static ?string $model = RemainingStock::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Stock::class;

    protected static ?string $navigationGroup = 'Stock';

    public static function getModelLabel(): string
    {
        return __('crud.remainingStocks.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.remainingStocks.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.remainingStocks.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    DateInput::make('date')
                        ->placeholder(__('Date')),

                    BaseSelectInput::make('store_id')
                        ->required()
                        ->placeholder('Store')
                        ->relationship('store', 'nickname'),

                    StatusSelectInput::make('status')
                        ->placeholder('Status'),
                ]),
            ]),

            Section::make('Stock Product')->schema([
                Grid::make(['default' => 1])->schema([
                    self::getProductsRepeater()
                ]),
            ])->hidden(fn ($operation) => $operation === 'edit' || $operation === 'view'),

        ]);
    }

    public static function table(Table $table): Table
    {
        $remainingStocks = RemainingStock::query();

        if (Auth::user()->hasRole('staff')) {
            $remainingStocks = $remainingStocks->where('created_by_id', Auth::id());
        }

        return $table
            ->poll('60s')
            ->query($remainingStocks)
            ->columns([
                TextColumn::make('date'),

                TextColumn::make('store.nickname'),

                TextColumn::make('createdBy.name')
                    ->hidden(fn () => !Auth::user()->hasRole('admin')),

                StatusColumn::make('status'),

                TextColumn::make('productRemainingStocks', 'Product Remaining Stocks')
                    ->label('Stocks')
                    ->html()
                    ->formatStateUsing(function (RemainingStock $record) {
                        return implode('<br>', $record->productRemainingStocks->map(function ($productRemainingStock) {
                            return "{$productRemainingStock->product->name} = {$productRemainingStock->quantity} {$productRemainingStock->product->unit->unit}";
                        })->toArray());
                    })
                    ->extraAttributes(['class' => 'whitespace-pre-wrap']),

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
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\ProductsRelationManager::class,
        ];
    }

    public static function getCreateButtonLabel(): string
    {
        return __('Create'); // Mengubah teks tombol menjadi "Create"
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRemainingStocks::route('/'),
            'create' => Pages\CreateRemainingStock::route('/create'),
            'view' => Pages\ViewRemainingStock::route('/{record}'),
            'edit' => Pages\EditRemainingStock::route('/{record}/edit'),
        ];
    }

    public static function getProductsRepeater(): Repeater
    {
        $products = Product::where('remaining', '1')->get()->map(function ($item) {
            return [
                'product_id' => $item->id,
                'quantity' => $item->quantity,
            ];
        })->toArray();

        return Repeater::make('productRemainingStocks')
            // ->label(__('crud.remainingStocks.products'))

            ->hiddenLabel()
            ->default($products)
            ->relationship()
            ->addable(false)
            ->deletable(false)
            ->schema([
                Select::make('product_id')
                    ->label('Product')
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->required()
                    ->native(false)
                    ->options(Product::where('remaining', '1')->get()->pluck('name','id')),
                TextInput::make('quantity')
                    ->required()
                    ->suffix(function ($get) {
                        $product = Product::find($get('product_id'));
                        return $product ? $product->unit->unit : '';
                    }),
            ]);
    }
}
