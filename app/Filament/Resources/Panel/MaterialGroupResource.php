<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Products;
use App\Filament\Clusters\Transaction\Settings;
use App\Filament\Columns\ActiveColumn;
use App\Filament\Forms\ActiveStatusSelect;
use App\Filament\Forms\BaseTextInput;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MaterialGroup;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\MaterialGroupResource\Pages;
use App\Filament\Resources\Panel\MaterialGroupResource\RelationManagers;
use Filament\Tables\Actions\ActionGroup;

class MaterialGroupResource extends Resource
{
    protected static ?string $model = MaterialGroup::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationGroup = 'Product';

    public static function getModelLabel(): string
    {
        return __('crud.materialGroups.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.materialGroups.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.materialGroups.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    BaseTextInput::make('name')
                        ->required()
                        ->string()
                        ->autofocus(),

                    ActiveStatusSelect::make('status'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('name')->searchable(),

                TextColumn::make('user.name'),

                ActiveColumn::make('status'),
            ])
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
            'index' => Pages\ListMaterialGroups::route('/'),
            'create' => Pages\CreateMaterialGroup::route('/create'),
            'view' => Pages\ViewMaterialGroup::route('/{record}'),
            'edit' => Pages\EditMaterialGroup::route('/{record}/edit'),
        ];
    }
}
