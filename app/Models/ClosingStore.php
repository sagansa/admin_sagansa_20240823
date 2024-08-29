<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClosingStore extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function closingCouriers()
    {
        return $this->belongsToMany(ClosingCourier::class);
    }

    public function shiftStore()
    {
        return $this->belongsTo(ShiftStore::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function transferBy()
    {
        return $this->belongsTo(User::class, 'transfer_by_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function cashlesses()
    {
        return $this->hasMany(Cashless::class);
    }

    public function dailySalaries()
    {
        return $this->belongsToMany(DailySalary::class);
    }

    public function fuelServices()
    {
        return $this->belongsToMany(FuelService::class);
    }

    public function invoicePurchases()
    {
        return $this->belongsToMany(InvoicePurchase::class);
    }
}
