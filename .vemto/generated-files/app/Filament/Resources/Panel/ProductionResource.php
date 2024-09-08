<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Production;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\ProductionResource\Pages;
use App\Filament\Resources\Panel\ProductionResource\RelationManagers;

class ProductionResource extends Resource
{
    protected static ?string $model = Production::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Stock';

    public static function getModelLabel(): string
    {
        return __('crud.productions.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.productions.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.productions.collectionTitle');
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

                    RichEditor::make('notes')
                        ->required()
                        ->string()
                        ->fileAttachmentsVisibility('public'),

                    Select::make('status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('created_by_id')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('approved_by_id')
                        ->required()
                        ->relationship('createdBy', 'name')
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
                TextColumn::make('store.name'),

                TextColumn::make('date')->since(),

                TextColumn::make('notes')->limit(255),

                TextColumn::make('status'),

                TextColumn::make('created_by_id'),

                TextColumn::make('createdBy.name'),
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
        return [
            RelationManagers\ProductionMainFromsRelationManager::class,
            RelationManagers\ProductionSupportFromsRelationManager::class,
            RelationManagers\ProductionTosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductions::route('/'),
            'create' => Pages\CreateProduction::route('/create'),
            'view' => Pages\ViewProduction::route('/{record}'),
            'edit' => Pages\EditProduction::route('/{record}/edit'),
        ];
    }
}
