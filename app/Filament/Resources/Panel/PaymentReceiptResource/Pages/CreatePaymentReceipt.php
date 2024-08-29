<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\PaymentReceiptResource;
use App\Models\DailySalary;
use App\Models\PaymentReceipt;

class CreatePaymentReceipt extends CreateRecord
{
    protected static string $resource = PaymentReceiptResource::class;
}
