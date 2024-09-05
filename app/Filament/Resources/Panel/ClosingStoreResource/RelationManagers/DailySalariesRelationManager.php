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
use App\Filament\Tables\DailySalaryTable;
use Filament\Resources\RelationManagers\RelationManager;

class DailySalariesRelationManager extends RelationManager
{
    protected static string $relationship = 'dailySalaries';

    protected static ?string $recordTitleAttribute = 'date';

    public function table(Table $table): Table
    {
        return $table
            ->columns(
                DailySalaryTable::schema()
            )
            ->filters([])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->action(function ($record) {
                        $record->pivot->delete();
                        $record->update(['status' => 1]);
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([

                //     Tables\Actions\DetachBulkAction::make()
                //         ->action(function ($records) {
                //                 foreach ($records as $record) {
                //                     $record->paymentReceipts()->detach();
                //                     $record->update(['status' => 1]);
                //                 }
                //             }),
                // ]),
            ]);
    }
}
