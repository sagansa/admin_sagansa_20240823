<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountCashless extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cashlessProvider()
    {
        return $this->belongsTo(CashlessProvider::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function storeCashless()
    {
        return $this->belongsTo(StoreCashless::class);
    }

    public function adminCashlesses()
    {
        return $this->belongsToMany(AdminCashless::class);
    }

    public function cashlesses()
    {
        return $this->hasMany(Cashless::class);
    }

    public function getAccountCashlessNameAttribute()
    {
        return $this->store->nickname . ' | ' . $this->cashlessProvider->name . ' | ' . $this->storeCashless->name;
    }
}
