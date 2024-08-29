<?php

namespace App\Filament\Resources\Panel\SalesOrderEmployeesResource\Pages;

use App\Filament\Resources\Panel\SalesOrderEmployeesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSalesOrderEmployees extends CreateRecord
{
    protected static string $resource = SalesOrderEmployeesResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['for'] = "2";
        $data['payment_status'] = "1";
        $data['delivery_status'] = "2";
        $data['ordered_by_id'] = Auth::id();
        $data['shipping_cost'] = "0";

        return $data;
    }
}
