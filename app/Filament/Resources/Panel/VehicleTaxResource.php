<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Asset;
use App\Filament\Clusters\Vehicles;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VehicleTax;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\VehicleTaxResource\Pages;
use App\Filament\Resources\Panel\VehicleTaxResource\RelationManagers;

class VehicleTaxResource extends Resource
{
    protected static ?string $model = VehicleTax::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Asset::class;

    protected static ?string $navigationGroup = 'Vehicle';

    public static function getModelLabel(): string
    {
        return __('crud.vehicleTaxes.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.vehicleTaxes.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.vehicleTaxes.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    ImageInput::make('image')

                        ->directory('images/VehicleTax'),

                    TextInput::make('amount_tax')
                        ->required()
                        ->numeric()
                        ->step(1),

                    Select::make('vehicle_id')
                        ->required()
                        ->relationship('vehicle', 'image')
                        ->searchable()
                        ->preload(),

                    DateInput::make('expired_date'),

                    Notes::make('notes'),

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

                TextColumn::make('amount_tax'),

                TextColumn::make('vehicle.image'),

                TextColumn::make('expired_date')->since(),

                TextColumn::make('notes')->limit(255),

                TextColumn::make('user.name'),
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
            'index' => Pages\ListVehicleTaxes::route('/'),
            'create' => Pages\CreateVehicleTax::route('/create'),
            'view' => Pages\ViewVehicleTax::route('/{record}'),
            'edit' => Pages\EditVehicleTax::route('/{record}/edit'),
        ];
    }
}
