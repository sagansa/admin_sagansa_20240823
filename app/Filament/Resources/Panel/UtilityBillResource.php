<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UtilityBill;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Panel\UtilityBillResource\Pages;
use App\Filament\Resources\Panel\UtilityBillResource\RelationManagers;

class UtilityBillResource extends Resource
{
    protected static ?string $model = UtilityBill::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Purchase';

    public static function getModelLabel(): string
    {
        return __('crud.utilityBills.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.utilityBills.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.utilityBills.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('utility_id')
                        ->required()
                        ->relationship('utility', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    DatePicker::make('date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    TextInput::make('amount')
                        ->required()
                        ->numeric()
                        ->step(1)
                        ->prefix('Rp'),

                    TextInput::make('initial_indiator')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('last_indicator')
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
                TextColumn::make('utility.name'),

                TextColumn::make('date')->since(),

                TextColumn::make('amount'),

                TextColumn::make('initial_indiator'),

                TextColumn::make('last_indicator'),
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
            'index' => Pages\ListUtilityBills::route('/'),
            'create' => Pages\CreateUtilityBill::route('/create'),
            'view' => Pages\ViewUtilityBill::route('/{record}'),
            'edit' => Pages\EditUtilityBill::route('/{record}/edit'),
        ];
    }
}
