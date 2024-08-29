<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\FuelService;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\FuelServiceResource\Pages;
use App\Filament\Resources\Panel\FuelServiceResource\RelationManagers;

class FuelServiceResource extends Resource
{
    protected static ?string $model = FuelService::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

    public static function getModelLabel(): string
    {
        return __('crud.fuelServices.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.fuelServices.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.fuelServices.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('supplier_id')
                        ->required()
                        ->relationship('supplier', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('vehicle_id')
                        ->required()
                        ->relationship('vehicle', 'image')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('payment_type_id')
                        ->required()
                        ->relationship('paymentType', 'name')
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

                    TextInput::make('km')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('liter')
                        ->nullable()
                        ->numeric()
                        ->step(1),

                    TextInput::make('amount')
                        ->required()
                        ->numeric()
                        ->step(1),

                    RichEditor::make('notes')
                        ->nullable()
                        ->string()
                        ->fileAttachmentsVisibility('public'),

                    Select::make('created_by_id')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('approved_by_id')
                        ->required()
                        ->relationship('createdBy', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('fuel_service')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options([
                            '1' => 'fuel',
                            '2' => 'service',
                        ]),

                    Select::make('status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options([
                            '1' => 'belum diperiksa',
                            '2' => 'valid',
                            '3' => 'perbaiki ',
                            '4' => 'periksa ulang',
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
                TextColumn::make('supplier.name'),

                TextColumn::make('vehicle.image'),

                TextColumn::make('paymentType.name'),

                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('km'),

                TextColumn::make('liter'),

                TextColumn::make('amount'),

                TextColumn::make('notes')->limit(255),

                TextColumn::make('created_by_id'),

                TextColumn::make('createdBy.name'),

                TextColumn::make('fuel_service'),

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
            'index' => Pages\ListFuelServices::route('/'),
            'create' => Pages\CreateFuelService::route('/create'),
            'view' => Pages\ViewFuelService::route('/{record}'),
            'edit' => Pages\EditFuelService::route('/{record}/edit'),
        ];
    }
}
