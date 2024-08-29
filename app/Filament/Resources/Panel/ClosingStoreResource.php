<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ClosingStore;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\ClosingStoreResource\Pages;
use App\Filament\Resources\Panel\ClosingStoreResource\RelationManagers;

class ClosingStoreResource extends Resource
{
    protected static ?string $model = ClosingStore::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Cash';

    public static function getModelLabel(): string
    {
        return __('crud.closingStores.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.closingStores.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.closingStores.collectionTitle');
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

                    DatePicker::make('date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    TextInput::make('cash_from_yesterday')
                        ->required()
                        ->numeric()
                        ->step(1)
                        ->prefix('Rp'),

                    TextInput::make('cash_for_tomorrow')
                        ->required()
                        ->numeric()
                        ->step(1)
                        ->prefix('Rp'),

                    TextInput::make('transfer_by_id')
                        ->nullable()
                        ->numeric()
                        ->step(1)
                        ->prefix('Rp'),

                    TextInput::make('total_cash_transfer')
                        ->required()
                        ->numeric()
                        ->step(1)
                        ->prefix('Rp'),

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

                    Select::make('created_by_id')
                        ->nullable()
                        ->relationship('createdBy', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('approved_by_id')
                        ->nullable()
                        ->relationship('transferBy', 'name')
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

                TextColumn::make('date')->since(),

                TextColumn::make('cash_from_yesterday'),

                TextColumn::make('cash_for_tomorrow'),

                TextColumn::make('transfer_by_id'),

                TextColumn::make('total_cash_transfer'),

                TextColumn::make('status'),

                TextColumn::make('notes')->limit(255),

                TextColumn::make('createdBy.name'),

                TextColumn::make('transferBy.name'),
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
            'index' => Pages\ListClosingStores::route('/'),
            'create' => Pages\CreateClosingStore::route('/create'),
            'view' => Pages\ViewClosingStore::route('/{record}'),
            'edit' => Pages\EditClosingStore::route('/{record}/edit'),
        ];
    }
}
