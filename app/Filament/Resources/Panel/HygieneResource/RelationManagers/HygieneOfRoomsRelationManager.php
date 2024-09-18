<?php

namespace App\Filament\Resources\Panel\HygieneResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Panel\HygieneResource;
use Filament\Resources\RelationManagers\RelationManager;

class HygieneOfRoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'hygieneOfRooms';

    protected static ?string $recordTitleAttribute = 'image';

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                Select::make('room_id')
                    ->required()
                    ->relationship('room', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                FileUpload::make('image')
                    ->rules(['image'])
                    ->nullable()
                    ->maxSize(1024)
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.name'),

                ImageColumn::make('image')->visibility('public'),
            ])
            ->filters([])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
