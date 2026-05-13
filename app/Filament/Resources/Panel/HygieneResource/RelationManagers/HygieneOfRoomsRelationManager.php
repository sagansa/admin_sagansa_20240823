<?php

namespace App\Filament\Resources\Panel\HygieneResource\RelationManagers;

use App\Filament\Forms\ImageInput;
use Filament\Forms;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\ActionGroup;

class HygieneOfRoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'hygieneOfRooms';

    protected static ?string $recordTitleAttribute = 'room.name';

    public function form(Schema $form): Schema
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                Select::make('room_id')
                    ->required()
                    ->relationship('room', 'name')
                    ->preload(),

                ImageInput::make('image')
                    ->multiple()
                    ->directory('images/Hygiene'),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.name'),

                ImageColumn::make('image')
                    ->label('Images')
                    ->circular()
                    ->stacked(),
            ])
            ->filters([])
            ->headerActions([\Filament\Actions\CreateAction::make()])
            ->actions([
                ActionGroup::make([
                    \Filament\Actions\EditAction::make(),
                    \Filament\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
