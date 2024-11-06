<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariantType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relasi
    public function values()
    {
        return $this->hasMany(VariantValue::class);
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variants')
            ->distinct();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeWithValues($query)
    {
        return $query->whereHas('values');
    }

    // Methods
    public function getActiveValues()
    {
        return $this->values()
            ->where('status', 1)
            ->get();
    }

    public function isUsedInProducts()
    {
        return $this->productVariants()->exists();
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($variantType) {
            // Hapus semua nilai varian terkait
            $variantType->values()->delete();

            // Hapus semua product variants terkait
            $variantType->productVariants()->delete();
        });
    }

    // Helper Methods
    public function getStats()
    {
        return [
            'total_values' => $this->values()->count(),
            'active_values' => $this->values()->where('status', 1)->count(),
            'products_count' => $this->products()->count(),
            'last_used' => $this->productVariants()->latest()->first()?->created_at
        ];
    }
}
