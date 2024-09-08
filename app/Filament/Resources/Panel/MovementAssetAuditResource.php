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
use App\Models\MovementAssetAudit;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Panel\MovementAssetAuditResource\Pages;
use App\Filament\Resources\Panel\MovementAssetAuditResource\RelationManagers;

class MovementAssetAuditResource extends Resource
{
    protected static ?string $model = MovementAssetAudit::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Asset::class;

    protected static ?string $navigationGroup = 'Movement';

    public static function getModelLabel(): string
    {
        return __('crud.movementAssetAudits.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.movementAssetAudits.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.movementAssetAudits.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    FileUpload::make('image')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    Select::make('movement_asset_id')
                        ->required()
                        ->relationship('movementAsset', 'image')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('good_cond_qty')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('bad_cond_qty')
                        ->required()
                        ->numeric()
                        ->step(1),

                    Select::make('movement_asset_result_id')
                        ->required()
                        ->relationship('movementAssetResult', 'date')
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
                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('movementAsset.image'),

                TextColumn::make('good_cond_qty'),

                TextColumn::make('bad_cond_qty'),

                TextColumn::make('movementAssetResult.date'),
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
            'index' => Pages\ListMovementAssetAudits::route('/'),
            'create' => Pages\CreateMovementAssetAudit::route('/create'),
            'view' => Pages\ViewMovementAssetAudit::route('/{record}'),
            'edit' => Pages\EditMovementAssetAudit::route('/{record}/edit'),
        ];
    }
}
