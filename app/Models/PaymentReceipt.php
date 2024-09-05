<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentReceipt extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fuelServices()
    {
        return $this->belongsToMany(FuelService::class);
    }

    public function dailySalaries()
    {
        return $this->belongsToMany(DailySalary::class);
    }

    public function invoicePurchases()
    {
        return $this->belongsToMany(InvoicePurchase::class);
    }
}
