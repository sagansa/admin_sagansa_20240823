<?php

namespace App\Filament\Resources\Panel\RequestPurchaseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\RequestPurchaseResource;
use App\Models\DetailRequest;
use App\Models\RequestPurchase;
use Illuminate\Support\Facades\Auth;

class CreateRequestPurchase extends CreateRecord
{
    protected static string $resource = RequestPurchaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
