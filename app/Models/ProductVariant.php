<?php

namespace App\Models;

use App\Models\Manufacturing\Production;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductVariant extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'delete_in_update'];

    public function product()
    {
        return $this->belongsTo(Product::class)->select([
            'id',
            'name',
            'type',
            'tax_ac_id',
            'brand_id',
            'category_id',
            'tax_type',
            'unit_id',
            'product_code',
            'product_cost',
            'product_cost_with_tax',
            'profit',
            'product_price',
            'offer_price',
            'quantity',
            'combo_price',
            'is_combo',
            'is_variant',
            'is_for_sale',
            'is_show_emi_on_pos',
            'is_manage_stock',
            'quantity',
        ]);
    }

    public function purchaseVariants()
    {
        return $this->hasMany(PurchaseProduct::class, 'product_variant_id')
            ->where('production_id', null)
            ->where('opening_stock_id', null)
            ->where('sale_return_product_id', null)
            ->where('daily_stock_product_id', null);
    }

    public function dailyStockVariants()
    {
        return $this->hasMany(DailyStockProduct::class, 'variant_id');
    }

    public function productionVariants()
    {
        return $this->hasMany(Production::class, 'variant_id');
    }

    public function saleVariants()
    {
        return $this->hasMany(SaleProduct::class, 'product_variant_id');
    }

    public function updateVariantCost()
    {
        $settings = DB::table('general_settings')->select('business')->first();
        $stockAccountingMethod = json_decode($settings->business, true)['stock_accounting_method'];

        if ($stockAccountingMethod == 1) {

            $ordering = 'asc';
        } else {

            $ordering = 'desc';
        }

        return $this->hasOne(PurchaseProduct::class, 'product_variant_id')->where('left_qty', '>', '0')
            ->orderBy('created_at', $ordering)->select('product_variant_id', 'net_unit_cost');
    }
}
