<?php

namespace App\Filament\Resources\Panel\RemainingStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\RemainingStockResource;
use Illuminate\Support\Facades\Auth;

class EditRemainingStock extends EditRecord
{
    protected static string $resource = RemainingStockResource::class;

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
