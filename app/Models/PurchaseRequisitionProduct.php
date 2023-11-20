<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequisitionProduct extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function requisitionUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
