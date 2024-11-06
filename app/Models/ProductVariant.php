<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'variant_type_id',
        'variant_value_id',
        'sku',
        'status'
    ];

    protected $casts = [
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relasi
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantType()
    {
        return $this->belongsTo(VariantType::class);
    }

    public function variantValue()
    {
        return $this->belongsTo(VariantValue::class);
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    // Methods
    public function getPriceForStore($storeId)
    {
        return $this->prices()
            ->where('store_id', $storeId)
            ->where('status', 1)
            ->value('price');
    }

    public function getFullName()
    {
        return "{$this->product->name} - {$this->variantType->name}: {$this->variantValue->name}";
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($variant) {
            if (empty($variant->sku)) {
                $variant->sku = self::generateSKU($variant);
            }
        });

        static::deleting(function ($variant) {
            $variant->prices()->delete();
        });
    }

    // Helper Methods
    protected static function generateSKU($variant)
    {
        $prefix = 'VAR';
        $productSku = $variant->product->sku ?? '';
        $random = strtoupper(substr(uniqid(), -4));

        return "{$prefix}-{$productSku}-{$random}";
    }
}
