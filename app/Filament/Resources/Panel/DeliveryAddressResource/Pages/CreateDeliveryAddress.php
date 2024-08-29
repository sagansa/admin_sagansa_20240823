<?php

namespace App\Filament\Resources\Panel\DeliveryAddressResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\DeliveryAddressResource;
use Illuminate\Support\Facades\Auth;

class CreateDeliveryAddress extends CreateRecord
{
    protected static string $resource = DeliveryAddressResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $user = Auth::user();
    //     if ($user->hasRole('customer')) {
    //         $data['for'] = "1"; //direct
    //     } elseif ($user->hasRole('admin') || $user->hasRole('storage-staff')) {
    //         $data['for'] = "3"; //online
    //     } elseif ($user->hasRole('sales')) {
    //         $data['for'] = "2"; //employee
    //     }

    //     $data['user_id'] = Auth::id();

    //     return $data;
    // }
}
