<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoicePurchase extends Model
{
    use HasFactory;

    // protected $guarded = [];

    protected $fillable = [
        'image',
        'payment_type_id',
        'store_id',
        'supplier_id',
        'date',
        'taxes',
        'discounts',
        'total_price',
        'notes',
        'created_by_id',
        'payment_status',
        'order_status',
    ];

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function paymentReceipts()
    {
        return $this->belongsToMany(PaymentReceipt::class);
    }

    public function detailInvoices()
    {
        return $this->hasMany(DetailInvoice::class);
    }

    public function closingStores()
    {
        return $this->belongsToMany(ClosingStore::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function getInvoicePurchaseNameAttribute()
    {
        $invoicePurchaseDetails = [
            ($this->supplier->name ?: ''),
            ($this->store->nickname ?: ''),
            ($this->date ?: ''),
            ('Rp ' . number_format($this->total_price) ?: ''),
        ];

        return implode("\n", $invoicePurchaseDetails);
    }
}
