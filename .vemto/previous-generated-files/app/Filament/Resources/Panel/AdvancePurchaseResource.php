<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AdvancePurchase;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\AdvancePurchaseResource\Pages;
use App\Filament\Resources\Panel\AdvancePurchaseResource\RelationManagers;

class AdvancePurchaseResource extends Resource
{
    protected static ?string $model = AdvancePurchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

    public static function getModelLabel(): string
    {
        return __('crud.advancePurchases.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.advancePurchases.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.advancePurchases.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    FileUpload::make('image')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    Select::make('cash_advance_id')
                        ->nullable()
                        ->relationship('cashAdvance', 'image')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('supplier_id')
                        ->required()
                        ->relationship('supplier', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('store_id')
                        ->required()
                        ->relationship('store', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    DatePicker::make('date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    TextInput::make('subtotal_price')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('discount_price')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('total_price')
                        ->required()
                        ->numeric()
                        ->step(1),

                    Select::make('user_id')
                        ->required()
                        ->relationship('user', 'name')
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
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('cashAdvance.image'),

                TextColumn::make('supplier.name'),

                TextColumn::make('store.name'),

                TextColumn::make('date')->since(),

                TextColumn::make('subtotal_price'),

                TextColumn::make('discount_price'),

                TextColumn::make('total_price'),

                TextColumn::make('user.name'),

                TextColumn::make('notes')->limit(255),

                TextColumn::make('status'),
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
            'index' => Pages\ListAdvancePurchases::route('/'),
            'create' => Pages\CreateAdvancePurchase::route('/create'),
            'view' => Pages\ViewAdvancePurchase::route('/{record}'),
            'edit' => Pages\EditAdvancePurchase::route('/{record}/edit'),
        ];
    }
}