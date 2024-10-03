<?php

namespace App\Filament\Resources\Panel\TransferStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\TransferStockResource;
use Illuminate\Support\Facades\Auth;

class CreateTransferStock extends CreateRecord
{
    protected static string $resource = TransferStockResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sent_by_id'] = Auth::id();
        $data['status'] = 1;

        return $data;
    }
}
