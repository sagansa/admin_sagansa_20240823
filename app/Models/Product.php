<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailAdvancePurchases()
    {
        return $this->hasMany(DetailAdvancePurchase::class);
    }

    public function detailSalesOrders()
    {
        return $this->hasMany(DetailSalesOrder::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function detailRequests()
    {
        return $this->hasMany(DetailRequest::class);
    }

    public function productionSupportFroms()
    {
        return $this->hasMany(ProductionSupportFrom::class);
    }

    public function productionTos()
    {
        return $this->hasMany(ProductionTo::class);
    }

    public function remainingStocks()
    {
        return $this->belongsToMany(RemainingStock::class);
    }

    public function selfConsumptions()
    {
        return $this->belongsToMany(SelfConsumption::class);
    }

    public function transferStocks()
    {
        return $this->belongsToMany(TransferStock::class);
    }

    public function movementAssets()
    {
        return $this->hasMany(MovementAsset::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function materialGroup()
    {
        return $this->belongsTo(MaterialGroup::class);
    }

    public function onlineCategory()
    {
        return $this->belongsTo(OnlineCategory::class);
    }

    public function storageStocks()
    {
        return $this->belongsToMany(StorageStock::class);
    }

    public function detailStockCards()
    {
        return $this->hasMany(DetailStockCard::class);
    }

    public function detailTransferCards()
    {
        return $this->hasMany(DetailTransferCard::class);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function getProductNameAttribute()
    {
        return $this->name . ' - ' . $this->unit->unit;
    }

    protected $casts = [
        'has_variants' => 'boolean',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    // Relasi ke varian produk
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Relasi ke harga produk
    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    // Relasi langsung ke tipe varian melalui product_variants
    public function variantTypes()
    {
        return $this->belongsToMany(VariantType::class, 'product_variants')
            ->distinct();
    }

    // Scope untuk produk aktif
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Scope untuk produk dengan varian
    public function scopeWithVariants($query)
    {
        return $query->where('has_variants', true);
    }

    // Scope untuk produk tanpa varian
    public function scopeWithoutVariants($query)
    {
        return $query->where('has_variants', false);
    }

    // Method untuk mendapatkan harga dasar produk di store tertentu
    public function getBasePriceForStore($storeId)
    {
        return $this->prices()
            ->where('store_id', $storeId)
            ->whereNull('product_variant_id')
            ->value('price');
    }

    // Method untuk mendapatkan harga varian produk di store tertentu
    public function getVariantPricesForStore($storeId)
    {
        return $this->prices()
            ->where('store_id', $storeId)
            ->whereNotNull('product_variant_id');
    }
}
