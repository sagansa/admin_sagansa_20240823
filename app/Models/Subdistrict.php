<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subdistrict extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function deliveryAddresses()
    {
        return $this->hasMany(DeliveryAddress::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function postalCodes()
    {
        return $this->hasMany(PostalCode::class);
    }
}
