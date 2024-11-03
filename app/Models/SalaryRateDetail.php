<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryRateDetail extends Model
{
    protected $guarded = [];

    protected $casts = [
        'years_of_service' => 'integer',
        'rate_per_hour' => 'integer',
    ];

    // Relationships
    public function salaryRate()
    {
        return $this->belongsTo(SalaryRate::class);
    }
}
