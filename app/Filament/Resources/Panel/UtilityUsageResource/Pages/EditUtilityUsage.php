<?php

namespace App\Filament\Resources\Panel\UtilityUsageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\UtilityUsageResource;
use Illuminate\Support\Facades\Auth;

class EditUtilityUsage extends EditRecord
{
    protected static string $resource = UtilityUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (Auth::user()->hasRole('admin') || Auth::user->hasRole('supervisor')) {
            $data['approved_by_id'] = Auth::id();
        }

        return $data;
    }
}
