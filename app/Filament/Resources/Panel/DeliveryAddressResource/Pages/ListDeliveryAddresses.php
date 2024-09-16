<?php

namespace App\Filament\Resources\Panel\DeliveryAddressResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\DeliveryAddressResource;
use Filament\Resources\Components\Tab;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ListDeliveryAddresses extends ListRecords
{
    protected static string $resource = DeliveryAddressResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'direct' => Tab::make()->query(fn ($query) => $query->where('for', '1')),
            'employee' => Tab::make()->query(fn ($query) => $query->where('for', '2')),
            'online' => Tab::make()->query(fn ($query) => $query->where('for', '3')),
        ];
    }
}
