<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleProduct extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function saleUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function purchaseSaleProductChains()
    {
        return $this->hasMany(PurchaseSaleProductChain::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function productTaxAccount()
    {
        return $this->belongsTo(Account::class, 'tax_ac_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'stock_warehouse_id')->select('id', 'warehouse_name', 'warehouse_code');
    }

    public function taxLedgerEntry()
    {
        return $this->hasOne(AccountLedger::class, 'sale_product_id');
    }
}
