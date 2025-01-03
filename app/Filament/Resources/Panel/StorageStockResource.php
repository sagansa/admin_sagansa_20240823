<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Bulks\ValidBulkAction;
use App\Filament\Clusters\Stock;
use App\Filament\Columns\StatusColumn;
use App\Filament\Filters\SelectStoreFilter;
use App\Filament\Forms\BaseSelectInput;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\NominalInput;
use App\Filament\Forms\StatusSelectInput;
use App\Filament\Forms\StoreSelect;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\StorageStock;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Panel\StorageStockResource\Pages;
use App\Filament\Resources\Panel\StorageStockResource\RelationManagers;
use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class StorageStockResource extends Resource
{
    protected static ?string $model = StorageStock::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Stock::class;

    protected static ?string $navigationGroup = 'Stock';

    public static function getModelLabel(): string
    {
        return __('crud.storageStocks.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.storageStocks.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.storageStocks.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    DateInput::make('date')
                        ->placeholder(__('Date')),

                    StoreSelect::make('store_id'),

                    StatusSelectInput::make('status')
                        ->placeholder('Status'),
                ]),
            ]),

            Section::make('Stock Product')->schema([
                Grid::make(['default' => 1])->schema([
                    self::getProductsRepeater()
                ]),
            ])->hidden(fn($operation) => $operation === 'edit' || $operation === 'view'),
        ]);
    }

    public static function table(Table $table): Table
    {
        $storageStocks = StorageStock::query();

        if (Auth::user()->hasRole('storage-staff')) {
            $storageStocks = $storageStocks->where('created_by_id', Auth::id());
        }

        return $table
            ->poll('60s')
            ->query($storageStocks)
            ->columns([
                TextColumn::make('date'),

                TextColumn::make('store.nickname'),


                TextColumn::make('createdBy.name')
                    ->hidden(fn() => !Auth::user()->hasRole('admin'))
                    ->toggleable(isToggledHiddenByDefault: true),

                StatusColumn::make('status'),

                TextColumn::make('productStorageStocks', 'Product Storage Stocks')
                    ->label('Stocks')
                    ->html()
                    ->formatStateUsing(function (StorageStock $record) {
                        return implode('<br>', $record->productStorageStocks->map(function ($productStorageStock) {
                            return "{$productStorageStock->product->name} = {$productStorageStock->quantity} {$productStorageStock->product->unit->unit}";
                        })->toArray());
                    })
                    ->extraAttributes(['class' => 'whitespace-pre-wrap']),

                TextColumn::make('created_at')
                    ->visible(fn($record) => auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
            ])
            ->filters([
                SelectStoreFilter::make('store_id')
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
                    ValidBulkAction::make('setStatusToValid')
                        ->action(function (Collection $records) {
                            StorageStock::whereIn('id', $records->pluck('id'))->update(['status' => 2]);
                        }),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStorageStocks::route('/'),
            // 'create' => Pages\CreateStorageStock::route('/create'),
            // 'view' => Pages\ViewStorageStock::route('/{record}'),
            // 'edit' => Pages\EditStorageStock::route('/{record}/edit'),
        ];
    }

    public static function getProductsRepeater(): Repeater
    {
        $products = Product::where('request', '1')->orderBy('name', 'asc')->get()->map(function ($item) {
            return [
                'product_id' => $item->id,
                'quantity' => $item->quantity,
            ];
        })->toArray();

        return Repeater::make('productStorageStocks')
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
                    ->options(Product::where('request', '1')->orderBy('name', 'asc')->get()->pluck('name', 'id'))
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
