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

    public function productConsumptions()
    {
        return $this->hasMany(ProductConsumption::class);
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

    // Generate slug before saving
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
}
