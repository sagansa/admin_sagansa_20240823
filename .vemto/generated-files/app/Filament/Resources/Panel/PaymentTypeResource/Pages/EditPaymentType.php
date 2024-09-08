<?php

namespace App\Filament\Resources\Panel\PaymentTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\PaymentTypeResource;

class EditPaymentType extends EditRecord
{
    protected static string $resource = PaymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
