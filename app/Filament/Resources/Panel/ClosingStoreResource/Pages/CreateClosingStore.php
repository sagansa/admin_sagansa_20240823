<?php

namespace App\Filament\Resources\Panel\ClosingStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\ClosingStoreResource;
use Illuminate\Support\Facades\Auth;

class CreateClosingStore extends CreateRecord
{
    protected static string $resource = ClosingStoreResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['status'] = 1;
        $data['created_by_id'] = Auth::id();

        return $data;
    }
}
