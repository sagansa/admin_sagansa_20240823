<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailInvoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function invoicePurchase()
    {
        return $this->belongsTo(InvoicePurchase::class);
    }

    public function detailRequest()
    {
        return $this->belongsTo(DetailRequest::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function productionMainFroms()
    {
        return $this->hasMany(ProductionMainFrom::class);
    }
}
