<?php

namespace App\Utils;

use App\Models\StockAdjustmentProduct;

class StockAdjustmentProductUtil
{
    public function addStockAdjustmentProduct($stockAdjustmentId, $request, $index)
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addStockAdjustmentProduct = new StockAdjustmentProduct();
        $addStockAdjustmentProduct->stock_adjustment_id = $stockAdjustmentId;
        $addStockAdjustmentProduct->warehouse_id = $request->warehouse_ids[$index];
        $addStockAdjustmentProduct->product_id = $request->product_ids[$index];
        $addStockAdjustmentProduct->product_variant_id = $variant_id;
        $addStockAdjustmentProduct->quantity = $request->quantities[$index];
        $addStockAdjustmentProduct->unit_id = $request->unit_ids[$index];
        $addStockAdjustmentProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addStockAdjustmentProduct->subtotal = $request->subtotals[$index];
        $addStockAdjustmentProduct->save();

        return $addStockAdjustmentProduct;
    }
}
