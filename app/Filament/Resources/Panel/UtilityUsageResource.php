<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UtilityUsage;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\UtilityUsageResource\Pages;
use App\Filament\Resources\Panel\UtilityUsageResource\RelationManagers;

class UtilityUsageResource extends Resource
{
    protected static ?string $model = UtilityUsage::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Stock';

    public static function getModelLabel(): string
    {
        return __('crud.utilityUsages.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.utilityUsages.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.utilityUsages.collectionTitle');
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

                    Select::make('utility_id')
                        ->required()
                        ->relationship('utility', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('result')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('status')
                        ->required()
                        ->string(),

                    RichEditor::make('notes')
                        ->nullable()
                        ->string()
                        ->fileAttachmentsVisibility('public'),

                    TextInput::make('created_by_id')
                        ->required()
                        ->numeric()
                        ->step(1),

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
                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('utility.name'),

                TextColumn::make('result'),

                TextColumn::make('status'),

                TextColumn::make('notes')->limit(255),

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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUtilityUsages::route('/'),
            'create' => Pages\CreateUtilityUsage::route('/create'),
            'view' => Pages\ViewUtilityUsage::route('/{record}'),
            'edit' => Pages\EditUtilityUsage::route('/{record}/edit'),
        ];
    }
}
