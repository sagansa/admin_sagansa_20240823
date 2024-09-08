<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Asset;
use App\Filament\Clusters\Vehicles;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\VehicleCertificate;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\VehicleCertificateResource\Pages;
use App\Filament\Resources\Panel\VehicleCertificateResource\RelationManagers;

class VehicleCertificateResource extends Resource
{
    protected static ?string $model = VehicleCertificate::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Asset::class;

    protected static ?string $navigationGroup = 'Vehicle';

    public static function getModelLabel(): string
    {
        return __('crud.vehicleCertificates.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.vehicleCertificates.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.vehicleCertificates.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('vehicle_id')
                        ->required()
                        ->relationship('vehicle', 'image')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('bpkb')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('stnk')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('name')
                        ->required()
                        ->string(),

                    TextInput::make('brand')
                        ->required()
                        ->string(),

                    TextInput::make('type')
                        ->required()
                        ->string(),

                    TextInput::make('category')
                        ->required()
                        ->string(),

                    TextInput::make('model')
                        ->required()
                        ->string(),

                    TextInput::make('manufacture_year')->required(),

                    TextInput::make('cylinder_capacity')
                        ->required()
                        ->string(),

                    TextInput::make('vehicle_identity_no')
                        ->required()
                        ->string(),

                    TextInput::make('engine_no')
                        ->required()
                        ->string(),

                    TextInput::make('color')
                        ->required()
                        ->string(),

                    TextInput::make('type_fuel')
                        ->required()
                        ->string(),

                    TextInput::make('lisence_plate_color')
                        ->required()
                        ->string(),

                    TextInput::make('registration_year')
                        ->required()
                        ->string(),

                    TextInput::make('bpkb_no')
                        ->required()
                        ->string(),

                    TextInput::make('location_code')
                        ->required()
                        ->string(),

                    TextInput::make('registration_queue_no')
                        ->required()
                        ->string(),

                    RichEditor::make('notes')
                        ->nullable()
                        ->string()
                        ->fileAttachmentsVisibility('public'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('vehicle.image'),

                TextColumn::make('bpkb'),

                TextColumn::make('stnk'),

                TextColumn::make('name'),

                TextColumn::make('brand'),

                TextColumn::make('type'),

                TextColumn::make('category'),

                TextColumn::make('model'),

                TextColumn::make('manufacture_year'),

                TextColumn::make('cylinder_capacity'),

                TextColumn::make('vehicle_identity_no'),

                TextColumn::make('engine_no'),

                TextColumn::make('color'),

                TextColumn::make('type_fuel'),

                TextColumn::make('lisence_plate_color'),

                TextColumn::make('registration_year'),

                TextColumn::make('bpkb_no'),

                TextColumn::make('location_code'),

                TextColumn::make('registration_queue_no'),

                TextColumn::make('notes')->limit(255),
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
            'index' => Pages\ListVehicleCertificates::route('/'),
            'create' => Pages\CreateVehicleCertificate::route('/create'),
            'view' => Pages\ViewVehicleCertificate::route('/{record}'),
            'edit' => Pages\EditVehicleCertificate::route('/{record}/edit'),
        ];
    }
}
