<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Asset;
use App\Filament\Clusters\Vehicles;
use App\Filament\Columns\ActiveColumn;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use App\Filament\Forms\StoreSelect;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use App\Models\Vehicle;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Panel\VehicleResource\Pages;
use App\Filament\Resources\Panel\VehicleResource\RelationManagers;
use Illuminate\Support\Facades\Auth;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Asset::class;

    protected static ?string $navigationGroup = 'Vehicle';

    public static function getModelLabel(): string
    {
        return __('crud.vehicles.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.vehicles.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.vehicles.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    ImageInput::make('image')

                        ->directory('images/Vehicle'),

                    Select::make('type')
                        ->hiddenLabel()
                        ->placeholder('Type')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->options([
                            '1' => 'motor',
                            '2' => 'mobil',
                            '3' => 'truk',
                        ]),

                    TextInput::make('no_register')
                        ->hiddenLabel()
                        ->placeholder('No Register')
                        ->required()
                        ->required()
                        ->string(),

                    StoreSelect::make('store_id')
                        ->hiddenLabel()
                        ->placeholder('Store')
                        ->required(),

                    Select::make('status')
                        ->hiddenLabel()
                        ->placeholder('Type')
                        ->required(fn () => Auth::user()->hasRole('admin'))
                        ->hidden(fn ($operation) => $operation === 'create')
                        ->disabled(fn () => Auth::user()->hasRole('staff'))
                        ->required()
                        ->required()
                        ->searchable()
                        ->preload()
                        ->options([
                            '1' => 'active',
                            '2' => 'inactive',
                        ]),

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

                TextColumn::make('no_register'),

                TextColumn::make('store.nickname'),

                ActiveColumn::make('status'),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'view' => Pages\ViewVehicle::route('/{record}'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
