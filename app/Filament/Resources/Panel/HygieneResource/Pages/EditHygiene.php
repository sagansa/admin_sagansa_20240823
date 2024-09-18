<?php

namespace App\Filament\Resources\Panel\HygieneResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\HygieneResource;
use Illuminate\Support\Facades\Auth;

class EditHygiene extends EditRecord
{
    protected static string $resource = HygieneResource::class;

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
