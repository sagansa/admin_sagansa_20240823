<?php

namespace App\Filament\Resources\Panel\RemainingStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\RemainingStoreResource;
use Illuminate\Support\Facades\Auth;

class CreateRemainingStore extends CreateRecord
{
    protected static string $resource = RemainingStoreResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['for'] = 'remaining_store';
        $data['user_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
