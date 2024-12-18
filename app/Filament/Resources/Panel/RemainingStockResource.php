<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Bulks\ValidBulkAction;
use App\Filament\Clusters\Stock;
use App\Filament\Columns\StatusColumn;
use App\Filament\Filters\SelectStoreFilter;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\NominalInput;
use App\Filament\Forms\StatusSelectInput;
use App\Filament\Forms\StoreSelect;
use App\Filament\Resources\Panel\RemainingStockResource\Pages;
use App\Filament\Resources\Panel\RemainingStockResource\RelationManagers;
use App\Models\Product;
use App\Models\RemainingStock;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
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
                Grid::make(['default' => 2])->schema([
                    DateInput::make('date')
                        ->placeholder(__('Date')),

                    StoreSelect::make('store_id'),

                    StatusSelectInput::make('status'),
                ]),
            ]),

            Section::make('Stock Product')->schema([
                Grid::make(['default' => 1])->schema([
                    self::getProductsRepeater(),
                ]),
            ])->hidden(fn($operation) => $operation === 'edit' || $operation === 'view'),

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
                    ->hidden(fn() => !Auth::user()->hasRole('admin'))
                    ->toggleable(isToggledHiddenByDefault: true),

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

                TextColumn::make('created_at')
                    ->visible(fn($record) => auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin')),

            ])
            ->filters([
                SelectStoreFilter::make('store_id'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ValidBulkAction::make('setStatusToValid')
                        ->action(function (Collection $records) {
                            RemainingStock::whereIn('id', $records->pluck('id'))->update(['status' => 2]);
                        }),
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
        $products = Product::where('remaining', '1')->orderBy('name', 'asc')->get()->map(function ($item) {
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
            ->columns([
                'md' => 10,
            ])
            ->schema([
                Select::make('product_id')
                    ->label('Product')
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->required()
                    ->options(Product::where('remaining', '1')->get()->pluck('name', 'id'))
                    ->columnSpan([
                        'md' => 5,
                    ]),
                NominalInput::make('quantity')
                    ->suffix(function ($get) {
                        $product = Product::find($get('product_id'));
                        return $product ? $product->unit->unit : '';
                    })
                    ->columnSpan([
                        'md' => 5,
                    ]),
            ]);
    }
}
