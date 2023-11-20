<?php

namespace App\Models\Manufacturing;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function ingredients()
    {
        return $this->hasMany(ProcessIngredient::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
