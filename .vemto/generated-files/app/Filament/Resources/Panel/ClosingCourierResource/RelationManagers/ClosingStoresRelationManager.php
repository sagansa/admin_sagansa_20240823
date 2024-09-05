<?php

namespace App\Filament\Resources\Panel\ClosingCourierResource\RelationManagers;

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
use Filament\Forms\Components\RichEditor;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Panel\ClosingCourierResource;

class ClosingStoresRelationManager extends RelationManager
{
    protected static string $relationship = 'closingStores';

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

                TextInput::make('cash_from_yesterday')
                    ->required()
                    ->numeric()
                    ->step(1),

                TextInput::make('cash_for_tomorrow')
                    ->required()
                    ->numeric()
                    ->step(1),

                TextInput::make('total_cash_transfer')
                    ->required()
                    ->numeric()
                    ->step(1),

                RichEditor::make('notes')
                    ->nullable()
                    ->string()
                    ->fileAttachmentsVisibility('public'),

                Select::make('created_by_id')
                    ->nullable()
                    ->relationship('createdBy', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                Select::make('approved_by_id')
                    ->nullable()
                    ->relationship('transferBy', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                Select::make('transfer_by_id')
                    ->nullable()
                    ->searchable()
                    ->preload()
                    ->native(false),

                Select::make('status')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->options([
                        '1' => 'belum diperiksa',
                        '2' => 'valid',
                        '3' => 'diperbaiki',
                        '4' => 'periksa ulang',
                    ]),
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

                TextColumn::make('cash_from_yesterday'),

                TextColumn::make('cash_for_tomorrow'),

                TextColumn::make('total_cash_transfer'),

                TextColumn::make('notes')->limit(255),

                TextColumn::make('createdBy.name'),

                TextColumn::make('transferBy.name'),

                TextColumn::make('transfer_by_id'),

                TextColumn::make('status'),
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
