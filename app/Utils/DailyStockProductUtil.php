<?php

namespace App\Utils;

use App\Models\DailyStockProduct;
use App\Models\Product;
use App\Models\ProductVariant;

class DailyStockProductUtil
{
    public function addDailyStockProduct($request, $dailyStockId, $index)
    {
        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addDailyStockProduct = new DailyStockProduct();
        $addDailyStockProduct->daily_stock_id = $dailyStockId;
        $addDailyStockProduct->product_id = $request->product_ids[$index];
        $addDailyStockProduct->variant_id = $variantId;
        $addDailyStockProduct->quantity = $request->quantities[$index];
        $addDailyStockProduct->unit_id = $request->unit_ids[$index];
        $addDailyStockProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
        $addDailyStockProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addDailyStockProduct->tax_percent = $request->tax_percents[$index];
        $addDailyStockProduct->tax_type = $request->tax_types[$index];
        $addDailyStockProduct->tax_amount = $request->tax_amounts[$index];
        $addDailyStockProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addDailyStockProduct->subtotal = $request->subtotals[$index];
        $addDailyStockProduct->save();

        $this->updateProductCost($request->product_ids[$index], $variantId, $request->tax_ac_ids[$index], $request->unit_costs_exc_tax[$index], $request->unit_costs_inc_tax[$index]);

        return $addDailyStockProduct;
    }

    public function updateDailyStockProduct($dailyStockId, $request, $index)
    {
        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $dailyStockProduct = DailyStockProduct::where('daily_stock_id', $dailyStockId)->where('id', $request->daily_stock_product_ids[$index])->first();

        $currTaxAcId = $dailyStockProduct ? $dailyStockProduct->tax_ac_id : null;

        $addOrEditDailyStockProduct = '';
        if ($dailyStockProduct) {

            $addOrEditDailyStockProduct = $dailyStockProduct;
        } else {

            $addOrEditDailyStockProduct = new DailyStockProduct();
        }

        $addOrEditDailyStockProduct->daily_stock_id = $dailyStockId;
        $addOrEditDailyStockProduct->product_id = $request->product_ids[$index];
        $addOrEditDailyStockProduct->variant_id = $variantId;
        $addOrEditDailyStockProduct->quantity = $request->quantities[$index];
        $addOrEditDailyStockProduct->unit_id = $request->unit_ids[$index];
        $addOrEditDailyStockProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
        $addOrEditDailyStockProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrEditDailyStockProduct->tax_percent = $request->tax_percents[$index];
        $addOrEditDailyStockProduct->tax_type = $request->tax_types[$index];
        $addOrEditDailyStockProduct->tax_amount = $request->tax_amounts[$index];
        $addOrEditDailyStockProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrEditDailyStockProduct->subtotal = $request->subtotals[$index];
        $addOrEditDailyStockProduct->is_delete_in_update = 0;
        $addOrEditDailyStockProduct->save();

        return ['addOrEditDailyStockProduct' => $addOrEditDailyStockProduct, 'currTaxAcId' => $currTaxAcId];
    }

    public function updateProductCost($productId, $variantId, $tax_ac_id, $unit_cost_exc_tax, $unit_cost_inc_tax)
    {
        $product = Product::where('id', $productId)->first();
        $product->tax_ac_id = $tax_ac_id;

        if ($variantId == null) {

            $product->product_cost = $unit_cost_exc_tax;
            $product->product_cost_with_tax = $unit_cost_inc_tax;
        }

        $product->save();

        if ($variantId) {

            $variant = ProductVariant::where('id', $variantId)->first();
            $variant->variant_cost = $unit_cost_exc_tax;
            $variant->variant_cost_with_tax = $unit_cost_inc_tax;
            $variant->save();
        }
    }
}
