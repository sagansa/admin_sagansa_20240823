<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Products;
use App\Filament\Clusters\Transaction\Settings;
use App\Filament\Forms\BaseTextInput;
use Filament\Forms;
use Filament\Tables;
use App\Models\Unit;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\UnitResource\Pages;
use App\Filament\Resources\Panel\UnitResource\RelationManagers;
use Filament\Tables\Actions\ActionGroup;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationGroup = 'Product';

    public static function getModelLabel(): string
    {
        return __('crud.units.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.units.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.units.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    BaseTextInput::make('name'),

                    BaseTextInput::make('unit'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([TextColumn::make('name')->searchable(), TextColumn::make('unit')->searchable()])
            ->filters([])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
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
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'view' => Pages\ViewUnit::route('/{record}'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
