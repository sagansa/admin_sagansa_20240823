<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\RelationManagers;

use App\Filament\Actions\DetachFuelServiceAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Tables\FuelServiceTable;
use Illuminate\Database\Eloquent\Model;

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
                // \Filament\Actions\CreateAction::make(),

                // \Filament\Actions\AttachAction::make()->form(
                //     fn(\Filament\Actions\AttachAction $action): array => [
                //         $action->getRecordSelect(),
                //     ]
                // ),
            ])
            ->actions([
                // \Filament\Actions\EditAction::make(),
                // \Filament\Actions\DeleteAction::make(),
                \Filament\Actions\DetachAction::make()
                    // ->label('Detach')
                    ->action(function ($record) {
                        $record->pivot->delete(); // Hapus hubungan pada tabel pivot
                        $record->update(['status' => 1]); // Ubah status menjadi 1
                    }),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    // \Filament\Actions\DeleteBulkAction::make(),

                    \Filament\Actions\DetachBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->paymentReceipts()->detach(); // Menghapus hubungan di tabel pivot
                                $record->update(['status' => 1]); // Mengubah status menjadi 1
                            }
                        }),
                ]),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->payment_for === 1;
    }
}
