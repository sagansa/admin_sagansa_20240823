<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostalCode extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

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

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function deliveryAddresses()
    {
        return $this->hasMany(DeliveryAddress::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
