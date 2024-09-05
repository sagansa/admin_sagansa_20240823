<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\RelationManagers;

use App\Filament\Actions\DetachFuelServiceAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Tables\FuelServiceTable;

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
                    // ->label('Detach')
                    ->action(function ($record) {
                        $record->pivot->delete(); // Hapus hubungan pada tabel pivot
                        $record->update(['status' => 1]); // Ubah status menjadi 1
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\DetachBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->paymentReceipts()->detach(); // Menghapus hubungan di tabel pivot
                                $record->update(['status' => 1]); // Mengubah status menjadi 1
                            }
                    }),
                ]),
            ]);
    }
}
