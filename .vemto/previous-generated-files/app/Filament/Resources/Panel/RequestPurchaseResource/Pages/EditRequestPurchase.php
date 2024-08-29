<?php

namespace App\Filament\Resources\Panel\RequestPurchaseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\RequestPurchaseResource;

class EditRequestPurchase extends EditRecord
{
    protected static string $resource = RequestPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
