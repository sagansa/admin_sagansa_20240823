<?php

namespace App\Filament\Resources\Panel\SalesOrderOnlinesResource\Pages;

use App\Filament\Resources\Panel\SalesOrderOnlinesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSalesOrderOnlines extends CreateRecord
{
    protected static string $resource = SalesOrderOnlinesResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['for'] = "3";
        $data['delivery_status'] = "1";
        $data['payment_status'] = "2";
        $data['ordered_by_id'] = Auth::id();
        $data['shipping_cost'] = "0";

        return $data;
    }
}
