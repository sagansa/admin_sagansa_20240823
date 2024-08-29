<?php

namespace App\Filament\Resources\Panel\FuelServiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\FuelServiceResource;
use Illuminate\Support\Facades\Auth;

class CreateFuelService extends CreateRecord
{
    protected static string $resource = FuelServiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['created_by_id'] = Auth::id();
        // $data['approved_by_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
