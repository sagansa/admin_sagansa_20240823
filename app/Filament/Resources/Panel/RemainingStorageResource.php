<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Bulks\ValidBulkAction;
use App\Filament\Clusters\Stock;
use App\Filament\Columns\StatusColumn;
use App\Filament\Filters\SelectStoreFilter;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\StockCardForm;
use App\Filament\Forms\StockRepeaterForm;
use App\Filament\Forms\StoreSelect;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RemainingStorage;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Panel\RemainingStorageResource\Pages;
use App\Filament\Resources\Panel\RemainingStorageResource\RelationManagers;
use App\Filament\Tables\StockCardTable;
use App\Filament\Tables\ValidAction;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class RemainingStorageResource extends Resource
{
    protected static ?string $model = RemainingStorage::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Stock::class;

    protected static ?string $navigationGroup = 'Stock';

    public static function getModelLabel(): string
    {
        return __('crud.remainingStorages.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.remainingStorages.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.remainingStorages.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(
            StockCardForm::getStockCardStorage(),
        );
    }

    public static function table(Table $table): Table
    {
        $query = RemainingStorage::query();

        if (Auth::user()->hasRole('staff')) {
            $query->where('user_id', Auth::id());
        }

        $query->where('for', 'remaining_storage');

        return $table
            ->poll('60s')
            ->query($query)
            ->columns(
                StockCardTable::schema(RemainingStorage::class)
            )
            ->filters([SelectStoreFilter::make('store_id')])
            ->actions(ValidAction::getAction(self::$model)['actions'])
            ->bulkActions(ValidAction::getAction(self::$model)['bulkActions'])
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRemainingStorages::route('/'),
            'create' => Pages\CreateRemainingStorage::route('/create'),
            'view' => Pages\ViewRemainingStorage::route('/{record}'),
            'edit' => Pages\EditRemainingStorage::route('/{record}/edit'),
        ];
    }
}
