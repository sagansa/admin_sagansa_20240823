<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClosingStore extends Model
{
    use HasFactory;

    // protected $guarded = [];

    protected $fillable = [
        'store_id',
        'shift_store_id',
        'date',
        'cash_from_yesterday',
        'cash_for_tomorrow',
        'transfer_by_id',
        'total_cash_transfer',
        'status',
        'notes',
        'created_by_id',
        'approved_by_id'
    ];

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

    public function closingCouriers()
    {
        return $this->belongsToMany(ClosingCourier::class);
    }

    public function getClosingStoreNameAttribute()
    {
        return $this->store->nickname .
            ' | ' . $this->shiftStore->name .
            ' | ' . $this->date .
            ' | Rp ' . number_format($this->total_cash_transfer, 0, ',', '.');
    }

    public static function boot()
    {
        parent::boot();

        self::updated(function (ClosingStore $closingStore) {
            if ($closingStore->is_attached) {
                $closingStore->invoicePurchases()->update(['status' => 2]);
                $closingStore->fuelServices()->update(['status' => 2]);
                $closingStore->dailySalaries()->update(['status' => 2]);
            } elseif(!$closingStore->is_attached) {
                $closingStore->invoicePurchases()->update(['status' => 1]);
                $closingStore->fuelServices()->update(['status' => 1]);
                $closingStore->dailySalaries()->update(['status' => 1]);
            }
        });
    }
}
