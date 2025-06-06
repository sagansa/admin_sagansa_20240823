<?php

namespace App\Filament\Resources\Panel\DeliveryAddressResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\DeliveryAddressResource;
use Illuminate\Support\Facades\Auth;

class CreateDeliveryAddress extends CreateRecord
{
    protected static string $resource = DeliveryAddressResource::class;
}
