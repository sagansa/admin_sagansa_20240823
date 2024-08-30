<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function requestPurchase()
    {
        return $this->belongsTo(RequestPurchase::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function detailInvoices()
    {
        return $this->hasMany(DetailInvoice::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->status = 1;
        });
    }

}
