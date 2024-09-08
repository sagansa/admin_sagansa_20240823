<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use App\Models\Store;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\StoreResource\Pages;
use App\Filament\Resources\Panel\StoreResource\RelationManagers;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Store';

    public static function getModelLabel(): string
    {
        return __('crud.stores.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.stores.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.stores.collectionTitle');
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

                    TextInput::make('nickname')
                        ->required()
                        ->string(),

                    TextInput::make('no_telp')
                        ->nullable()
                        ->string(),

                    TextInput::make('email')
                        ->required()
                        ->string()
                        ->unique('stores', 'email', ignoreRecord: true)
                        ->email(),

                    Select::make('user_id')
                        ->required()
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('name'),

                TextColumn::make('nickname'),

                TextColumn::make('no_telp'),

                TextColumn::make('email'),

                TextColumn::make('user.name'),

                TextColumn::make('status'),
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
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'view' => Pages\ViewStore::route('/{record}'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
