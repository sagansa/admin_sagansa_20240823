<?php

namespace App\Filament\Resources\Panel\PaymentTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\PaymentTypeResource;

class ListPaymentTypes extends ListRecords
{
    protected static string $resource = PaymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
