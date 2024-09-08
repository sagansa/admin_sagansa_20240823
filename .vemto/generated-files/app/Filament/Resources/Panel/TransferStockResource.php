<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TransferStock;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\TransferStockResource\Pages;
use App\Filament\Resources\Panel\TransferStockResource\RelationManagers;

class TransferStockResource extends Resource
{
    protected static ?string $model = TransferStock::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Stock';

    public static function getModelLabel(): string
    {
        return __('crud.transferStocks.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.transferStocks.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.transferStocks.collectionTitle');
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

                    Select::make('from_store_id')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('to_store_id')
                        ->required()
                        ->relationship('storeFrom', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    RichEditor::make('notes')
                        ->nullable()
                        ->string()
                        ->fileAttachmentsVisibility('public'),

                    Select::make('status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('approved_by_id')
                        ->nullable()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('sent_by_id')
                        ->required()
                        ->relationship('approvedBy', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('received_by_id')
                        ->required()
                        ->relationship('receivedBy', 'name')
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
                TextColumn::make('date')->since(),

                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('from_store_id'),

                TextColumn::make('storeFrom.name'),

                TextColumn::make('notes')->limit(255),

                TextColumn::make('status'),

                TextColumn::make('approved_by_id'),

                TextColumn::make('approvedBy.name'),

                TextColumn::make('receivedBy.name'),
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
            'index' => Pages\ListTransferStocks::route('/'),
            'create' => Pages\CreateTransferStock::route('/create'),
            'view' => Pages\ViewTransferStock::route('/{record}'),
            'edit' => Pages\EditTransferStock::route('/{record}/edit'),
        ];
    }
}
