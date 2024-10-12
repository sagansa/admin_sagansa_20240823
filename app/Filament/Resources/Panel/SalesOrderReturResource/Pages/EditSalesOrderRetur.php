<?php

namespace App\Filament\Resources\Panel\SalesOrderReturResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\SalesOrderReturResource;

class EditSalesOrderRetur extends EditRecord
{
    protected static string $resource = SalesOrderReturResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
