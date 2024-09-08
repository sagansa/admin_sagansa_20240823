<?php

namespace App\Filament\Resources\Panel\StoreCashlessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\StoreCashlessResource;

class ListStoreCashlesses extends ListRecords
{
    protected static string $resource = StoreCashlessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
