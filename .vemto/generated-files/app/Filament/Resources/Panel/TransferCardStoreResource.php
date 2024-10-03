<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\TransferCardStore;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Panel\TransferCardStoreResource\Pages;
use App\Filament\Resources\Panel\TransferCardStoreResource\RelationManagers;

class TransferCardStoreResource extends Resource
{
    protected static ?string $model = TransferCardStore::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

    public static function getModelLabel(): string
    {
        return __('crud.transferCardStores.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.transferCardStores.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.transferCardStores.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    DatePicker::make('date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    FileUpload::make('image')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    TextInput::make('status')
                        ->required()
                        ->string(),

                    Select::make('from_store_id')
                        ->required()
                        ->relationship('storeFrom', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('to_store_id')
                        ->required()
                        ->relationship('storeTo', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('sent_by_id')
                        ->required()
                        ->relationship('sentBy', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('received_by_id')
                        ->required()
                        ->relationship('receivedBy', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('for')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options([
                            'store' => 'Store',
                            'storage' => 'Storage',
                        ]),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('date')->since(),

                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('status'),

                TextColumn::make('storeFrom.name'),

                TextColumn::make('storeTo.name'),

                TextColumn::make('sentBy.name'),

                TextColumn::make('receivedBy.name'),

                TextColumn::make('for'),
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
            'index' => Pages\ListTransferCardStores::route('/'),
            'create' => Pages\CreateTransferCardStore::route('/create'),
            'view' => Pages\ViewTransferCardStore::route('/{record}'),
            'edit' => Pages\EditTransferCardStore::route('/{record}/edit'),
        ];
    }
}
