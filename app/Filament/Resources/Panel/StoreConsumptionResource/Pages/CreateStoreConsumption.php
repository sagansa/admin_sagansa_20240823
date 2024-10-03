<?php

namespace App\Filament\Resources\Panel\StoreConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\StoreConsumptionResource;
use Illuminate\Support\Facades\Auth;

class CreateStoreConsumption extends CreateRecord
{
    protected static string $resource = StoreConsumptionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['for'] = 'store_consumption';
        $data['user_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
