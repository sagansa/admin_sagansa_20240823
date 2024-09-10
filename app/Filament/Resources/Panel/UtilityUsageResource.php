<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Stock;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\StatusColumn;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use App\Filament\Forms\StatusSelectInput;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UtilityUsage;
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
use App\Filament\Resources\Panel\UtilityUsageResource\Pages;
use App\Filament\Resources\Panel\UtilityUsageResource\RelationManagers;

class UtilityUsageResource extends Resource
{
    protected static ?string $model = UtilityUsage::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Stock::class;

    protected static ?string $navigationGroup = 'Consumption';

    public static function getModelLabel(): string
    {
        return __('crud.utilityUsages.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.utilityUsages.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.utilityUsages.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    ImageInput::make('image'),

                    Select::make('utility_id')
                        ->required()
                        ->relationship('utility', 'name')
                        ->preload()
                        ->native(false),

                    TextInput::make('result')
                        ->required()
                        ->numeric(),

                    StatusSelectInput::make('status')
                        ->required()

                        ->hidden(fn ($operation) => $operation === 'create'),

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
                ImageOpenUrlColumn::make('image')->visibility('public'),

                TextColumn::make('utility.name'),

                TextColumn::make('result'),

                StatusColumn::make('status'),

                TextColumn::make('createdBy.name'),

                TextColumn::make('approvedBy.name'),
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
            'index' => Pages\ListUtilityUsages::route('/'),
            'create' => Pages\CreateUtilityUsage::route('/create'),
            'view' => Pages\ViewUtilityUsage::route('/{record}'),
            'edit' => Pages\EditUtilityUsage::route('/{record}/edit'),
        ];
    }
}
