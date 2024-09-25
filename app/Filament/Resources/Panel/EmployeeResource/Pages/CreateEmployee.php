<?php

namespace App\Filament\Resources\Panel\EmployeeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\EmployeeResource;
use Illuminate\Support\Facades\Auth;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = Auth::id();
        $data['is_banned'] = 0;
        $data['is_wfa'] = 0;
        $data['employee_status_id'] = 1;

        return $data;
    }
}
