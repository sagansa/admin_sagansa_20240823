<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariantValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'variant_type_id',
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'integer'
    ];

    // Relasi ke VariantType
    public function variantType()
    {
        return $this->belongsTo(VariantType::class);
    }

    // Relasi ke ProductVariant
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
