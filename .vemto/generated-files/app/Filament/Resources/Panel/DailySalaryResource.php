<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DailySalary;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Panel\DailySalaryResource\Pages;
use App\Filament\Resources\Panel\DailySalaryResource\RelationManagers;

class DailySalaryResource extends Resource
{
    protected static ?string $model = DailySalary::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

    public static function getModelLabel(): string
    {
        return __('crud.dailySalaries.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.dailySalaries.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.dailySalaries.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('store_id')
                        ->required()
                        ->relationship('store', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('shift_store_id')
                        ->required()
                        ->relationship('shiftStore', 'name')
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
                        ->step(1),

                    Select::make('payment_type_id')
                        ->required()
                        ->relationship('paymentType', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('store.name'),

                TextColumn::make('shiftStore.name'),

                TextColumn::make('date')->since(),

                TextColumn::make('amount'),

                TextColumn::make('paymentType.name'),

                TextColumn::make('created_by_id'),

                TextColumn::make('createdBy.name'),

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
            'index' => Pages\ListDailySalaries::route('/'),
            'create' => Pages\CreateDailySalary::route('/create'),
            'view' => Pages\ViewDailySalary::route('/{record}'),
            'edit' => Pages\EditDailySalary::route('/{record}/edit'),
        ];
    }
}
