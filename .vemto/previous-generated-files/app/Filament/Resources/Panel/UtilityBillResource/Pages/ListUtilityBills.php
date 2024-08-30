<?php

namespace App\Filament\Resources\Panel\UtilityBillResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\UtilityBillResource;

class ListUtilityBills extends ListRecords
{
    protected static string $resource = UtilityBillResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
