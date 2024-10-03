<?php

namespace App\Filament\Resources\Panel\ProductionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\ProductionResource;
use Illuminate\Support\Facades\Auth;

class CreateProduction extends CreateRecord
{
    protected static string $resource = ProductionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
