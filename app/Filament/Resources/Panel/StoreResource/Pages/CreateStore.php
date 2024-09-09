<?php

namespace App\Filament\Resources\Panel\StoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\StoreResource;
use Illuminate\Support\Facades\Auth;

class CreateStore extends CreateRecord
{
    protected static string $resource = StoreResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = Auth::id();

        return $data;
    }
}
