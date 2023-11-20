<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function purchaseSaleChains()
    {
        return $this->hasMany(PurchaseSaleProductChain::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function purchaseUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
