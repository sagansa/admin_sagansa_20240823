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

    protected function afterCreate(): void
    {
        $order = $this->record;
        
        try {
            // 1. Kirim Notifikasi ke Admin
            \Illuminate\Support\Facades\Mail::to('asapanganbangsa@gmail.com')
                ->send(new \App\Mail\NewSalesOrderDirectMail($order));
            
            // 2. Kirim Tanda Terima ke Customer
            $customerEmail = $order->orderedBy?->email;
            if ($customerEmail) {
                \Illuminate\Support\Facades\Mail::to($customerEmail)
                    ->send(new \App\Mail\SalesOrderDirectCustomerMail($order));
            }
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send Sales Order Direct email: ' . $e->getMessage());
        }
    }
}
