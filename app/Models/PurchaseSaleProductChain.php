<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseSaleProductChain extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function purchaseProduct()
    {
        return $this->belongsTo(PurchaseProduct::class, 'purchase_product_id');
    }
}
