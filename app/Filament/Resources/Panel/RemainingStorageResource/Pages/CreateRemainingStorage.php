<?php

namespace App\Filament\Resources\Panel\RemainingStorageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\RemainingStorageResource;
use Illuminate\Support\Facades\Auth;

class CreateRemainingStorage extends CreateRecord
{
    protected static string $resource = RemainingStorageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['for'] = 'remaining_storage';
        $data['user_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
