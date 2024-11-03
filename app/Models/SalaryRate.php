<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryRate extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'effective_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function salaryRateDetails()
    {
        return $this->hasMany(SalaryRateDetail::class);
    }
}
