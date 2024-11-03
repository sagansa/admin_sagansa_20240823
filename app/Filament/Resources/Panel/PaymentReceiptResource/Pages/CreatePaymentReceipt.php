<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\PaymentReceiptResource;
use App\Models\DailySalary;
use App\Models\InvoicePurchase;
use App\Models\PaymentReceipt;

class CreatePaymentReceipt extends CreateRecord
{
    protected static string $resource = PaymentReceiptResource::class;

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        if ($record->payment_for == 3) {
            foreach ($record->invoicePurchases as $invoicePurchase) {
                $invoicePurchase->payment_status = 2;
                $invoicePurchase->save();
            }
        } elseif ($record->payment_for == 2) {
            foreach ($record->dailySalaries as $dailySalary) {
                $dailySalary->status = 2;
                $dailySalary->save();
            }
        } elseif ($record->payment_for == 1) {
            foreach ($record->fuelServices as $fuelService) {
                $fuelService->status = 2;
                $fuelService->save();
            }
        }
    }
}
