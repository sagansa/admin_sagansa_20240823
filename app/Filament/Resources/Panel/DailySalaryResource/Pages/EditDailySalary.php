<?php

namespace App\Filament\Resources\Panel\DailySalaryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\DailySalaryResource;
use Illuminate\Support\Facades\Auth;

class EditDailySalary extends EditRecord
{
    protected static string $resource = DailySalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (Auth::user()->hasRole('admin')) {
            $data['approved_by_id'] = Auth::id();
        }

        return $data;
    }
}
