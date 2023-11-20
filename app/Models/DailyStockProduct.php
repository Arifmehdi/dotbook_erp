<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyStockProduct extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function purchaseSaleChains()
    {
        return $this->hasMany(PurchaseSaleProductChain::class, 'id', 'daily_stock_product_id');
    }

    public function dailyStockUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
