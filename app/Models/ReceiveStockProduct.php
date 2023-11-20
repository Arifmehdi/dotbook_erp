<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveStockProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function receiveStock()
    {
        return $this->belongsTo(ReceiveStock::class, 'receive_stock_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function receiveUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function poProduct()
    {
        return $this->belongsTo(PurchaseOrderProduct::class, 'purchase_order_product_id');
    }
}
