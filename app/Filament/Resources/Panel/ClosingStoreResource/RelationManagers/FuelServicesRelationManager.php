<?php

namespace App\Filament\Resources\Panel\ClosingStoreResource\RelationManagers;

use App\Filament\Forms\ImageInput;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\ClosingStoreResource;
use App\Filament\Tables\FuelServiceTable;
use Filament\Resources\RelationManagers\RelationManager;

class FuelServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'fuelServices';

    protected static ?string $recordTitleAttribute = 'date';

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                Select::make('supplier_id')
                    ->required()
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload(),

                Select::make('vehicle_id')
                    ->required()
                    ->relationship('vehicle', 'image')
                    ->searchable()
                    ->preload(),

                Select::make('payment_type_id')
                    ->required()
                    ->relationship('paymentType', 'name')
                    ->searchable()
                    ->preload(),

                ImageInput::make('image')
                    ->disk('public')
                    ->directory('images/FuelService'),

                TextInput::make('km')
                    ->required()
                    ->numeric()
                    ->step(1),

                TextInput::make('liter')
                    ->nullable()
                    ->numeric()
                    ->step(1),

                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->step(1),

                RichEditor::make('notes')
                    ->nullable()
                    ->string()
                    ->fileAttachmentsVisibility('public'),

                Select::make('created_by_id')
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('approved_by_id')
                    ->required()
                    ->relationship('createdBy', 'name')
                    ->searchable()
                    ->preload(),

                Select::make('fuel_service')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options([
                        '1' => 'fuel',
                        '2' => 'service',
                    ]),

                Select::make('status')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options([
                        '1' => 'belum diperiksa',
                        '2' => 'valid',
                        '3' => 'perbaiki ',
                        '4' => 'periksa ulang',
                    ]),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns(
                FuelServiceTable::schema()
            )
            ->filters([])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),

                // Tables\Actions\AttachAction::make()->form(
                //     fn(Tables\Actions\AttachAction $action): array => [
                //         $action->getRecordSelect(),
                //     ]
                // ),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make()
                    ->action(function ($record) {
                        $record->pivot->delete();
                        $record->update(['status' => 1]);
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),

                    // Tables\Actions\DetachBulkAction::make()
                    //     ->action(function ($records) {
                    //             foreach ($records as $record) {
                    //                 $record->paymentReceipts()->detach();
                    //                 $record->update(['status' => 1]);
                    //             }
                    //         }),
                // ]),
            ]);
    }
}
