<?php

namespace App\Filament\Resources\Panel\ClosingCourierResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\ClosingCourierResource;
use Illuminate\Support\Facades\Auth;

class CreateClosingCourier extends CreateRecord
{
    protected static string $resource = ClosingCourierResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['status'] = 1;
        $data['created_by_id'] = Auth::id();

        return $data;
    }
}
