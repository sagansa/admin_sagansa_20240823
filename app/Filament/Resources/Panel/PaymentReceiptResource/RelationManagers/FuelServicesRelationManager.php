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
