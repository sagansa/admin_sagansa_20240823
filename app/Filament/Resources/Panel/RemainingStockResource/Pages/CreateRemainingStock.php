<?php

namespace App\Filament\Resources\Panel\RemainingStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\RemainingStockResource;
use Illuminate\Support\Facades\Auth;

class CreateRemainingStock extends CreateRecord
{
    protected static string $resource = RemainingStockResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
