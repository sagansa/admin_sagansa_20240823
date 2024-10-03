<?php

namespace App\Filament\Tables;

use App\Filament\Bulks\ValidBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;

class ValidAction
{
    public static function getAction($modelClass)
    {
        return [
            'actions' => [
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
            ],
            'bulkActions' => [
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->action(function () {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                        }),
                    ValidBulkAction::make('setStatusToValid')
                        ->label('Set Status to Valid')
                        ->action(function (Collection $records) use ($modelClass) {
                            $modelClass::whereIn('id', $records->pluck('id'))->update(['status' => 2]);
                        })
                        ->color('success'),
                    ValidBulkAction::make('setStatusToPerbaiki')
                        ->label('Set Status to Perbaiki')
                        ->action(function (Collection $records) use ($modelClass) {
                            $modelClass::whereIn('id', $records->pluck('id'))->update(['status' => 3]);
                        })
                        ->color('danger'),
                ]),
            ],
        ];
    }
}
