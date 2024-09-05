<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\DetachAction;
use Illuminate\Database\Eloquent\Model;

class DetachFuelServiceAction extends DetachAction
{
    public function action(Model $record): void
    {
        // Detach record
        parent::action($record);

        // Ubah status menjadi 1
        $record->update(['status' => 1]);
    }
}
