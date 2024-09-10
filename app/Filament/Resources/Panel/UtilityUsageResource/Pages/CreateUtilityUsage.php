<?php

namespace App\Filament\Resources\Panel\UtilityUsageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\UtilityUsageResource;
use Illuminate\Support\Facades\Auth;

class CreateUtilityUsage extends CreateRecord
{
    protected static string $resource = UtilityUsageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['status'] = 1;
        $data['created_by_id'] = Auth::id();

        return $data;
    }
}
