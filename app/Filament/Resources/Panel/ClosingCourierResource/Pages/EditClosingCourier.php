<?php

namespace App\Filament\Resources\Panel\ClosingCourierResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\ClosingCourierResource;
use Illuminate\Support\Facades\Auth;

class EditClosingCourier extends EditRecord
{
    protected static string $resource = ClosingCourierResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (Auth::user()->hasRole('admin')) {
            $data['assigned_by_id'] = Auth::id();
        }

        return $data;
    }
}
