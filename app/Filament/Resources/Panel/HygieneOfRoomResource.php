<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Store;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\HygieneOfRoom;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Panel\HygieneOfRoomResource\Pages;
use App\Filament\Resources\Panel\HygieneOfRoomResource\RelationManagers;

class HygieneOfRoomResource extends Resource
{
    protected static ?string $model = HygieneOfRoom::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Store::class;

    protected static ?string $navigationGroup = 'Hygiene';

    public static function getModelLabel(): string
    {
        return __('crud.hygieneOfRooms.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.hygieneOfRooms.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.hygieneOfRooms.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('hygiene_id')
                        ->required()
                        ->relationship('hygiene', 'notes')
                        ->searchable()
                        ->preload()
                        ->native(false),

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
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('hygiene.notes'),

                TextColumn::make('room.name'),

                ImageColumn::make('image')->visibility('public'),
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
            'index' => Pages\ListHygieneOfRooms::route('/'),
            'create' => Pages\CreateHygieneOfRoom::route('/create'),
            'view' => Pages\ViewHygieneOfRoom::route('/{record}'),
            'edit' => Pages\EditHygieneOfRoom::route('/{record}/edit'),
        ];
    }
}
