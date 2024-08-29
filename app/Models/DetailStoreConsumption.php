<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailStoreConsumption extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function productConsumption()
    {
        return $this->belongsTo(ProductConsumption::class);
    }
}
