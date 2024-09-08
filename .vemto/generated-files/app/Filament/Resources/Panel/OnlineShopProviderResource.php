<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\OnlineShopProvider;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\OnlineShopProviderResource\Pages;
use App\Filament\Resources\Panel\OnlineShopProviderResource\RelationManagers;

class OnlineShopProviderResource extends Resource
{
    protected static ?string $model = OnlineShopProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Setting';

    public static function getModelLabel(): string
    {
        return __('crud.onlineShopProviders.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.onlineShopProviders.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.onlineShopProviders.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    TextInput::make('name')
                        ->required()
                        ->string()
                        ->autofocus(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([TextColumn::make('name')])
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
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOnlineShopProviders::route('/'),
            'create' => Pages\CreateOnlineShopProvider::route('/create'),
            'view' => Pages\ViewOnlineShopProvider::route('/{record}'),
            'edit' => Pages\EditOnlineShopProvider::route('/{record}/edit'),
        ];
    }
}
