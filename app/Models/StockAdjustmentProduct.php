<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustmentProduct extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class, 'stock_adjustment_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function stockAdjustmentUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
