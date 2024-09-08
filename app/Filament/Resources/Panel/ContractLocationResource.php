<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Store;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ContractLocation;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Panel\ContractLocationResource\Pages;
use App\Filament\Resources\Panel\ContractLocationResource\RelationManagers;

class ContractLocationResource extends Resource
{
    protected static ?string $model = ContractLocation::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Store::class;

    protected static ?string $navigationGroup = 'Store';

    public static function getModelLabel(): string
    {
        return __('crud.contractLocations.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.contractLocations.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.contractLocations.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('location_id')
                        ->required()
                        ->relationship('location', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    DatePicker::make('from_date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    DatePicker::make('until_date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    TextInput::make('nominal_contract')
                        ->required()
                        ->numeric()
                        ->step(1),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('location.name'),

                TextColumn::make('from_date')->since(),

                TextColumn::make('until_date')->since(),

                TextColumn::make('nominal_contract'),
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
            'index' => Pages\ListContractLocations::route('/'),
            'create' => Pages\CreateContractLocation::route('/create'),
            'view' => Pages\ViewContractLocation::route('/{record}'),
            'edit' => Pages\EditContractLocation::route('/{record}/edit'),
        ];
    }
}
