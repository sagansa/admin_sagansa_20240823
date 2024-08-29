<?php

namespace App\Filament\Resources\Panel\SalesOrderOnlinesResource\Pages;

use App\Filament\Resources\Panel\SalesOrderOnlinesResource;
use App\Models\SalesOrderOnline;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditSalesOrderOnlines extends EditRecord
{
    protected static string $resource = SalesOrderOnlinesResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (Auth::user()->hasRole('storage-staff')) {
            $data['assigned_by_id'] = Auth::id();
        }

        return $data;
    }
}
