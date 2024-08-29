<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\RelationManagers;

use App\Filament\Columns\CurrencyColumn;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Panel\PaymentReceiptResource;

class FuelServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'fuelServices';

    protected static ?string $recordTitleAttribute = 'image';

    // public function form(Form $form): Form
    // {
    //     return $form->schema([
    //         Grid::make(['default' => 1])->schema([
    //             Select::make('supplier_id')
    //                 ->required()
    //                 ->relationship('supplier', 'name')
    //                 ->searchable()
    //                 ->preload()
    //                 ->native(false),

    //             Select::make('vehicle_id')
    //                 ->required()
    //                 ->relationship('vehicle', 'no_register')
    //                 ->searchable()
    //                 ->preload()
    //                 ->native(false),

    //             Select::make('payment_type_id')
    //                 ->required()
    //                 ->relationship('paymentType', 'name')
    //                 ->searchable()
    //                 ->preload()
    //                 ->native(false),

    //             FileUpload::make('image')
    //                 ->rules(['image'])
    //                 ->nullable()
    //                 ->maxSize(1024)
    //                 ->image()
    //                 ->imageEditor()
    //                 ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

    //             TextInput::make('km')
    //                 ->required()
    //                 ->numeric()
    //                 ->step(1),

    //             TextInput::make('liter')
    //                 ->nullable()
    //                 ->numeric()
    //                 ->step(1),

    //             TextInput::make('amount')
    //                 ->required()
    //                 ->numeric()
    //                 ->step(1),

    //             RichEditor::make('notes')
    //                 ->nullable()
    //                 ->string()
    //                 ->fileAttachmentsVisibility('public'),

    //             Select::make('created_by_id')
    //                 ->required()
    //                 ->searchable()
    //                 ->preload()
    //                 ->native(false),

    //             Select::make('approved_by_id')
    //                 ->required()
    //                 ->relationship('createdBy', 'name')
    //                 ->searchable()
    //                 ->preload()
    //                 ->native(false),

    //             Select::make('fuel_service')
    //                 ->required()
    //                 ->searchable()
    //                 ->preload()
    //                 ->native(false)
    //                 ->options([
    //                     '1' => 'fuel',
    //                     '2' => 'service',
    //                 ]),

    //             Select::make('status')
    //                 ->required()
    //                 ->searchable()
    //                 ->preload()
    //                 ->native(false)
    //                 ->options([
    //                     '1' => 'belum diperiksa',
    //                     '2' => 'valid',
    //                     '3' => 'perbaiki ',
    //                     '4' => 'periksa ulang',
    //                 ]),
    //         ]),
    //     ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier.name'),

                TextColumn::make('vehicle.no_register'),

                TextColumn::make('km'),

                TextColumn::make('liter'),

                CurrencyColumn::make('amount'),

                TextColumn::make('createdBy.name'),

                TextColumn::make('fuel_service')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'fuel',
                            '2' => 'service',
                        }),

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
