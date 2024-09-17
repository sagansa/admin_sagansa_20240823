<?php

namespace App\Filament\Resources\Panel\ReadinessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\ReadinessResource;
use Illuminate\Support\Facades\Auth;

class CreateReadiness extends CreateRecord
{
    protected static string $resource = ReadinessResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = '1';
        $data['created_by_id'] = Auth::id();

        return $data;
    }
}
