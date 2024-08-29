<?php

namespace App\Filament\Resources\Panel\AdvancePurchaseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\AdvancePurchaseResource;
use Illuminate\Support\Facades\Auth;

class CreateAdvancePurchase extends CreateRecord
{
    protected static string $resource = AdvancePurchaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = Auth::id();
        $data['status'] = '1';

        return $data;
    }
}
