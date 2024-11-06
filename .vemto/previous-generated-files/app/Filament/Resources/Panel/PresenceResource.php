<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\Presence;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Panel\PresenceResource\Pages;
use App\Filament\Resources\Panel\PresenceResource\RelationManagers;

class PresenceResource extends Resource
{
    protected static ?string $model = Presence::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

    public static function getModelLabel(): string
    {
        return __('crud.presences.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.presences.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.presences.collectionTitle');
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

                    Select::make('shift_store_id')
                        ->required()
                        ->relationship('shiftStore', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('status')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('image_in')
                        ->nullable()
                        ->string(),

                    DateTimePicker::make('start_date_time')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    TextInput::make('latitude_in')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('longitude_in')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('image_out')
                        ->nullable()
                        ->string(),

                    DateTimePicker::make('end_date_time')
                        ->rules(['date'])
                        ->nullable()
                        ->native(false),

                    TextInput::make('latitude_out')
                        ->nullable()
                        ->numeric()
                        ->step(1),

                    TextInput::make('longitude_out')
                        ->nullable()
                        ->numeric()
                        ->step(1),

                    Select::make('created_by_id')
                        ->nullable()
                        ->relationship('createdBy', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('approved_by_id')
                        ->nullable()
                        ->relationship('approvedBy', 'name')
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

                TextColumn::make('shiftStore.name'),

                TextColumn::make('status'),

                TextColumn::make('image_in'),

                TextColumn::make('start_date_time')->since(),

                TextColumn::make('latitude_in'),

                TextColumn::make('longitude_in'),

                TextColumn::make('image_out'),

                TextColumn::make('end_date_time')->since(),

                TextColumn::make('latitude_out'),

                TextColumn::make('longitude_out'),

                TextColumn::make('createdBy.name'),

                TextColumn::make('approvedBy.name'),
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
            'index' => Pages\ListPresences::route('/'),
            'create' => Pages\CreatePresence::route('/create'),
            'view' => Pages\ViewPresence::route('/{record}'),
            'edit' => Pages\EditPresence::route('/{record}/edit'),
        ];
    }
}
