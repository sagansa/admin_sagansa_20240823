<?php

namespace App\Filament\Resources\Panel\TransferCardStorageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\TransferCardStorageResource;
use Illuminate\Support\Facades\Auth;

class CreateTransferCardStorage extends CreateRecord
{
    protected static string $resource = TransferCardStorageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['for'] = 'storage';
        $data['sent_by_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
