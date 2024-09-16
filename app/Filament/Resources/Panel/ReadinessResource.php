<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\HRD;
use App\Filament\Forms\ImageInput;
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
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Panel\ReadinessResource\Pages;
use App\Filament\Resources\Panel\ReadinessResource\RelationManagers;

class ReadinessResource extends Resource
{
    protected static ?string $model = Readiness::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Personal Data';

    protected static ?string $cluster = HRD::class;

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
                    ImageInput::make('image_selfie')
                        ->disk('public')
                        ->directory('images/Readiness'),

                    ImageInput::make('left_hand')
                        ->disk('public')
                        ->directory('images/Readiness'),

                    ImageInput::make('right_hand')
                        ->disk('public')
                        ->directory('images/Readiness'),

                    Select::make('status')
                        ->required()
                        ->default('1')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options([
                            '1' => 'belum diperiksa',
                            '2' => 'valid',
                            '3' => 'diperbaiki',
                            '4' => 'periksa ulang',
                        ]),

                    RichEditor::make('notes')
                        ->nullable()
                        ->string()
                        ->fileAttachmentsVisibility('public'),

                    TextInput::make('created_by_id')
                        ->nullable()
                        ->numeric()
                        ->step(1),

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
