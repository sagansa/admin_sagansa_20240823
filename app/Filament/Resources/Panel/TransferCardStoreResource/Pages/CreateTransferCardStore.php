<?php

namespace App\Filament\Resources\Panel\TransferCardStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\TransferCardStoreResource;
use Illuminate\Support\Facades\Auth;

class CreateTransferCardStore extends CreateRecord
{
    protected static string $resource = TransferCardStoreResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['for'] = 'store';
        $data['sent_by_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
