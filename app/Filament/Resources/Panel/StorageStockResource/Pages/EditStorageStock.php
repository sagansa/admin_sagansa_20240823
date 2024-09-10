<?php

namespace App\Filament\Resources\Panel\StorageStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\StorageStockResource;
use Illuminate\Support\Facades\Auth;

class EditStorageStock extends EditRecord
{
    protected static string $resource = StorageStockResource::class;

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
