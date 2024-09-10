<?php

namespace App\Filament\Resources\Panel\StorageStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\StorageStockResource;
use Illuminate\Support\Facades\Auth;

class CreateStorageStock extends CreateRecord
{
    protected static string $resource = StorageStockResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
