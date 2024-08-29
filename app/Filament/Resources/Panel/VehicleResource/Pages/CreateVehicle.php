<?php

namespace App\Filament\Resources\Panel\VehicleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\VehicleResource;
use Illuminate\Support\Facades\Auth;

class CreateVehicle extends CreateRecord
{
    protected static string $resource = VehicleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
