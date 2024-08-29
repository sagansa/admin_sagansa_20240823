<?php

namespace App\Filament\Resources\Panel\SalesOrderEmployeesResource\Pages;

use App\Filament\Resources\Panel\SalesOrderEmployeesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditSalesOrderEmployees extends EditRecord
{
    protected static string $resource = SalesOrderEmployeesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (Auth::user()->hasRole('admin')) {
            $data['assigned_by_id'] = Auth::id();
        }

        return $data;
    }
}
