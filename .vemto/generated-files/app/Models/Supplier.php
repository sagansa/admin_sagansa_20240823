<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'province_id',
        'district_id',
        'bank_id',
        'bank_account_name',
        'bank_account_no',
        'status',
        'image',
        'user_id',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advancePurchases()
    {
        return $this->hasMany(AdvancePurchase::class);
    }

    public function fuelServices()
    {
        return $this->hasMany(FuelService::class);
    }

    public function invoicePurchases()
    {
        return $this->hasMany(InvoicePurchase::class);
    }

    public function postalCode()
    {
        return $this->belongsTo(PostalCode::class);
    }

    public function paymentReceipts()
    {
        return $this->hasMany(PaymentReceipt::class);
    }

    public function getSupplierNameAttribute()
    {
        if ($this->bank_account_no != null) {
            return $this->name .
                ' | ' .
                $this->bank->name .
                ' | ' .
                $this->bank_account_name .
                ' | ' .
                $this->bank_account_no;
        } else {
            return $this->name;
        }
    }
}
