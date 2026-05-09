<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailAdvancePurchase extends Model
{

    protected $connection = 'mysql';
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function advancePurchase()
    {
        return $this->belongsTo(AdvancePurchase::class);
    }
}
