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
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Panel\PresenceResource\Pages;
use App\Filament\Resources\Panel\PresenceResource\RelationManagers;

class PresenceResource extends Resource
{
    protected static ?string $model = Presence::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'HRD';

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

                    DateTimePicker::make('datetime_in')
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

                    DateTimePicker::make('datetime_out')
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

                    FileUpload::make('image_in')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    FileUpload::make('image_out')
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
                TextColumn::make('store.name'),

                TextColumn::make('shiftStore.name'),

                TextColumn::make('datetime_in')->since(),

                TextColumn::make('latitude_in'),

                TextColumn::make('longitude_in'),

                TextColumn::make('datetime_out')->since(),

                TextColumn::make('latitude_out'),

                TextColumn::make('longitude_out'),

                ImageColumn::make('image_in')->visibility('public'),

                ImageColumn::make('image_out')->visibility('public'),

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
            'index' => Pages\ListPresences::route('/'),
            'create' => Pages\CreatePresence::route('/create'),
            'view' => Pages\ViewPresence::route('/{record}'),
            'edit' => Pages\EditPresence::route('/{record}/edit'),
        ];
    }
}
