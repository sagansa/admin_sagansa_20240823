<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Bulks\ValidBulkAction;
use App\Filament\Clusters\Stock;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\StatusColumn;
use App\Filament\Forms\BaseSelect;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use App\Filament\Forms\StatusSelectInput;
use App\Filament\Forms\StoreSelect;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TransferStock;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\TransferStockResource\Pages;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Collection;

class TransferStockResource extends Resource
{
    protected static ?string $model = TransferStock::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Stock::class;

    protected static ?string $navigationGroup = 'Stock';

    public static function getModelLabel(): string
    {
        return __('crud.transferStocks.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.transferStocks.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.transferStocks.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([

                    ImageInput::make('image')
                        ->directory('images/TransferStock'),

                ]),

                Grid::make(['default' => 2])->schema([

                    DateInput::make('date'),

                    StoreSelect::make('from_store_id')
                        ->required()
                        ->relationship(
                            name: 'storeFrom',
                            titleAttribute: 'nickname',
                            modifyQueryUsing: fn (Builder $query) => $query->where('status', '<>', 8)->orderBy('name', 'asc'),)
                        ->preload()
                        ->reactive(),

                    StoreSelect::make('to_store_id')
                        ->required()
                        ->relationship(
                            name: 'storeTo',
                            titleAttribute: 'nickname',
                            modifyQueryUsing: fn (Builder $query) => $query->where('status', '<>', 8)->orderBy('name', 'asc'),)
                        ->preload()
                        ->reactive(),

                    StatusSelectInput::make('status'),

                    BaseSelect::make('received_by_id')
                        ->relationship('receivedBy', 'name', fn (Builder $query) => $query
                            ->whereHas('roles', fn (Builder $query) => $query
                                ->where('name', 'staff') || $query
                                ->where('name', 'supervisor')))
                        ->searchable(),

                    BaseSelect::make('sent_by_id')
                        ->relationship('sentBy', 'name', fn (Builder $query) => $query
                            ->whereHas('roles', fn (Builder $query) => $query
                                ->where('name', 'staff') || $query
                                ->where('name', 'supervisor')))
                        ->searchable(),
                ]),
            ]),

            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Repeater::make('productTransferStocks')
                ]),
            ]),

            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Notes::make('notes'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                ImageOpenUrlColumn::make('image'),

                TextColumn::make('date'),

                TextColumn::make('storeFrom.nickname'),

                TextColumn::make('storeTo.nickname'),

                TextColumn::make('productTransferStocks', 'Product Transfer Stocks')
                    ->label('Transfer Stocks')
                    ->html()
                    ->formatStateUsing(function (TransferStock $record) {
                        return implode('<br>', $record->productTransferStocks->map(function ($productTransferStock) {
                            return "{$productTransferStock->product->name} = {$productTransferStock->quantity} {$productTransferStock->product->unit->unit}";
                        })->toArray());
                    })
                    ->extraAttributes(['class' => 'whitespace-pre-wrap']),

                StatusColumn::make('status'),

                TextColumn::make('receivedBy.name'),

                TextColumn::make('sentBy.name'),

                TextColumn::make('approvedBy.name'),
            ])
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
                    ValidBulkAction::make('setStatusToValid')
                        ->action(function (Collection $records) {
                            TransferStock::whereIn('id', $records->pluck('id'))->update(['status' => 2]);
                        }),
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
            'index' => Pages\ListTransferStocks::route('/'),
            'create' => Pages\CreateTransferStock::route('/create'),
            'view' => Pages\ViewTransferStock::route('/{record}'),
            'edit' => Pages\EditTransferStock::route('/{record}/edit'),
        ];
    }
}
