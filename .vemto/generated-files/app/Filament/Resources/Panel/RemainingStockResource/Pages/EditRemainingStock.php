<?php

namespace App\Filament\Resources\Panel\RemainingStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\RemainingStockResource;

class EditRemainingStock extends EditRecord
{
    protected static string $resource = RemainingStockResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
