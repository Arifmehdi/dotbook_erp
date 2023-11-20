<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseByScaleWeight extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }
}
