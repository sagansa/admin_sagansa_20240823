<?php

namespace App\Filament\Resources\Panel\PermitEmployeeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\PermitEmployeeResource;
use Illuminate\Support\Facades\Auth;

class CreatePermitEmployee extends CreateRecord
{
    protected static string $resource = PermitEmployeeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['created_by_id'] = Auth::id();
        // $data['approved_by_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
