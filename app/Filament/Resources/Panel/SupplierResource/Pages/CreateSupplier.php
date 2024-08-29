<?php

namespace App\Filament\Resources\Panel\SupplierResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\SupplierResource;
use Illuminate\Support\Facades\Auth;

class CreateSupplier extends CreateRecord
{
    protected static string $resource = SupplierResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
