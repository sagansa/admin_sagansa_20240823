<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_hours' => 'integer',
        'rate_per_hour' => 'integer',
        'base_salary' => 'integer',
        'allowances' => 'array',
        'deductions' => 'array',
        'total_salary' => 'integer',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_DRAFT = '1';
    const STATUS_APPROVED = '2';
    const STATUS_PAID = '3';
    const STATUS_CANCELLED = '8';

    public static function getStatusText($status)
    {
        return [
            self::STATUS_DRAFT => 'draft',
            self::STATUS_APPROVED => 'disetujui',
            self::STATUS_PAID => 'dibayar',
            self::STATUS_CANCELLED => 'dibatalkan',
        ][$status] ?? 'unknown';
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function salaryRate()
    {
        return $this->belongsTo(SalaryRate::class);
    }
}
