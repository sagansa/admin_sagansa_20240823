<?php

namespace App\Filament\Resources\Panel\ClosingStoreResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Panel\ClosingStoreResource;
use Filament\Resources\RelationManagers\RelationManager;

class DailySalariesRelationManager extends RelationManager
{
    protected static string $relationship = 'dailySalaries';

    protected static ?string $recordTitleAttribute = 'date';

    public function form(Form $form): Form
    {
        return $form->schema([
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

                Select::make('status')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false),

                Select::make('created_by_id')
                    ->nullable()
                    ->searchable()
                    ->preload()
                    ->native(false),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('store.name'),

                TextColumn::make('shiftStore.name'),

                TextColumn::make('date')->since(),

                TextColumn::make('amount'),

                TextColumn::make('paymentType.name'),

                TextColumn::make('status'),

                TextColumn::make('created_by_id'),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make(),

                Tables\Actions\AttachAction::make()->form(
                    fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                    ]
                ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
