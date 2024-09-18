<?php

namespace App\Filament\Resources\Panel\HygieneResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\HygieneResource;
use Illuminate\Support\Facades\Auth;

class CreateHygiene extends CreateRecord
{
    protected static string $resource = HygieneResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['created_by_id'] = Auth::id();
        $data['status'] = 1;

        return $data;
    }
}
