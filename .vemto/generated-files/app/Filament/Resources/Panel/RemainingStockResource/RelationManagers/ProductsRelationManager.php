<?php

namespace App\Filament\Resources\Panel\RemainingStockResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Panel\RemainingStockResource;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                TextInput::make('name')
                    ->required()
                    ->string()
                    ->autofocus(),

                Select::make('unit_id')
                    ->required()
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                TextInput::make('slug')
                    ->required()
                    ->string(),

                TextInput::make('sku')
                    ->nullable()
                    ->string(),

                TextInput::make('barcode')
                    ->nullable()
                    ->string(),

                RichEditor::make('description')
                    ->nullable()
                    ->string()
                    ->fileAttachmentsVisibility('public'),

                FileUpload::make('image')
                    ->rules(['image'])
                    ->nullable()
                    ->maxSize(1024)
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                TextInput::make('request')
                    ->required()
                    ->numeric()
                    ->step(1),

                TextInput::make('remaining')
                    ->required()
                    ->numeric()
                    ->step(1),

                Select::make('payment_type_id')
                    ->required()
                    ->relationship('paymentType', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                Select::make('material_group_id')
                    ->required()
                    ->relationship('materialGroup', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                Select::make('online_category_id')
                    ->required()
                    ->relationship('onlineCategory', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                DateTimePicker::make('deleted_at')
                    ->rules(['date'])
                    ->nullable()
                    ->native(false),

                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->step(1)
                    ->autofocus(),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),

                TextColumn::make('unit.name'),

                TextColumn::make('slug'),

                TextColumn::make('sku'),

                TextColumn::make('barcode'),

                TextColumn::make('description')->limit(255),

                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('request'),

                TextColumn::make('remaining'),

                TextColumn::make('paymentType.name'),

                TextColumn::make('materialGroup.name'),

                TextColumn::make('onlineCategory.name'),

                TextColumn::make('user.name'),

                TextColumn::make('deleted_at')->since(),

                TextColumn::make('quantity'),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make(),

                Tables\Actions\AttachAction::make()->form(
                    fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),

                        TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->step(1)
                            ->autofocus(),
                    ]
                ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
