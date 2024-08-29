<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UtilityProvider extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function utilities()
    {
        return $this->hasMany(Utility::class);
    }
}
