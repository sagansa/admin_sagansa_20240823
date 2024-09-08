<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\Readiness;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Panel\ReadinessResource\Pages;
use App\Filament\Resources\Panel\ReadinessResource\RelationManagers;

class ReadinessResource extends Resource
{
    protected static ?string $model = Readiness::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'HRD';

    public static function getModelLabel(): string
    {
        return __('crud.readinesses.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.readinesses.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.readinesses.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    RichEditor::make('notes')
                        ->nullable()
                        ->string()
                        ->fileAttachmentsVisibility('public'),

                    FileUpload::make('image_selfie')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    FileUpload::make('left_hand')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    FileUpload::make('right_hand')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    Select::make('status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('created_by_id')
                        ->nullable()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('approved_by_id')
                        ->nullable()
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
                TextColumn::make('notes')->limit(255),

                ImageColumn::make('image_selfie')->visibility('public'),

                ImageColumn::make('left_hand')->visibility('public'),

                ImageColumn::make('right_hand')->visibility('public'),

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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReadinesses::route('/'),
            'create' => Pages\CreateReadiness::route('/create'),
            'view' => Pages\ViewReadiness::route('/{record}'),
            'edit' => Pages\EditReadiness::route('/{record}/edit'),
        ];
    }
}
