<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Asset;
use App\Filament\Clusters\Movements;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\MovementAssetResult;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\MovementAssetResultResource\Pages;
use App\Filament\Resources\Panel\MovementAssetResultResource\RelationManagers;

class MovementAssetResultResource extends Resource
{
    protected static ?string $model = MovementAssetResult::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Asset::class;

    protected static ?string $navigationGroup = 'Movement';

    public static function getModelLabel(): string
    {
        return __('crud.movementAssetResults.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.movementAssetResults.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.movementAssetResults.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('store_id')
                        ->required()
                        ->relationship('store', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    DatePicker::make('date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    Select::make('status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('user_id')
                        ->required()
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    RichEditor::make('notes')
                        ->nullable()
                        ->string()
                        ->fileAttachmentsVisibility('public'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('store.name'),

                TextColumn::make('date')->since(),

                TextColumn::make('status'),

                TextColumn::make('user.name'),

                TextColumn::make('notes')->limit(255),
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
            'index' => Pages\ListMovementAssetResults::route('/'),
            'create' => Pages\CreateMovementAssetResult::route('/create'),
            'view' => Pages\ViewMovementAssetResult::route('/{record}'),
            'edit' => Pages\EditMovementAssetResult::route('/{record}/edit'),
        ];
    }
}
