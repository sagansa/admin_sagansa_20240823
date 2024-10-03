<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RemainingStorage;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Panel\RemainingStorageResource\Pages;
use App\Filament\Resources\Panel\RemainingStorageResource\RelationManagers;

class RemainingStorageResource extends Resource
{
    protected static ?string $model = RemainingStorage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

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
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('for')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options([
                            'remaining store' => 'Remaining store',
                            'remaining storage' => 'Remaining storage',
                            'employee consumption' => 'Employee consumption',
                            'store consumption' => 'Store consumption',
                        ]),

                    DatePicker::make('date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    Select::make('store_id')
                        ->required()
                        ->relationship('store', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('user_id')
                        ->required()
                        ->relationship('user', 'name')
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
                TextColumn::make('for'),

                TextColumn::make('date')->since(),

                TextColumn::make('store.name'),

                TextColumn::make('user.name'),
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
            'index' => Pages\ListRemainingStorages::route('/'),
            'create' => Pages\CreateRemainingStorage::route('/create'),
            'view' => Pages\ViewRemainingStorage::route('/{record}'),
            'edit' => Pages\EditRemainingStorage::route('/{record}/edit'),
        ];
    }
}
