<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Store as ClustersStore;
use App\Filament\Forms\BaseTextInput;
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
use Filament\Tables\Actions\ActionGroup;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = ClustersStore::class;

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
                    BaseTextInput::make('name')
                        ->autofocus(),

                    BaseTextInput::make('nickname'),

                    TextInput::make('no_telp')
                        ->label('Telephone')
                        ->nullable()
                        ->string(),

                    BaseTextInput::make('email')
                        ->unique('stores', 'email', ignoreRecord: true)
                        ->email(),

                    Select::make('status')
                        ->required()
                        ->preload()
                        ->options([
                            '1' => 'warung',
                            '2' => 'gudang',
                            '3' => 'produksi',
                            '4' => 'warung + gudang',
                            '5' => 'warung + produksi',
                            '6' => 'gudang + produksi',
                            '7' => 'warung + gudang + produksi',
                            '8' => 'tidak aktif'
                        ]),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),

                TextColumn::make('nickname')->searchable()->sortable(),

                TextColumn::make('no_telp')->searchable(),

                TextColumn::make('email')->searchable(),

                TextColumn::make('user.name'),

                TextColumn::make('status')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'warung',
                            '2' => 'gudang',
                            '3' => 'produksi',
                            '4' => 'warung + gudang',
                            '5' => 'warung + produksi',
                            '6' => 'gudang + produksi',
                            '7' => 'warung + gudang + produksi',
                            '8' => 'tidak aktif',
                            default => $state,
                        }
                    ),
            ])
            ->filters([])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->defaultSort('nickname', 'asc');
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
