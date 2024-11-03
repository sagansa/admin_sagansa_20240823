<?php

namespace App\Filament\Resources\Panel\SalesOrderDirectsResource\Pages;

use App\Filament\Resources\Panel\SalesOrderDirectsResource;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Notification as LaravelNotification;
use App\Notifications\NewDataCreatedNotification;

class CreateSalesOrderDirects extends CreateRecord
{
    protected static string $resource = SalesOrderDirectsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['for'] = '1';
        $data['payment_status'] = '1';
        $data['delivery_status'] = '1';
        $data['ordered_by_id'] = Auth::id();

        return $data;
    }
}
