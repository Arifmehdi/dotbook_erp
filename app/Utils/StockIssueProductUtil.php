<?php

namespace App\Utils;

use App\Models\StockIssueProduct;

class StockIssueProductUtil
{
    public function addStockIssueProduct($request, $stockIssueId)
    {
        $index = 0;
        foreach ($request->product_ids as $productId) {

            $warehouse_id = isset($request->warehouse_ids) ? ($request->warehouse_ids[$index] ? $request->warehouse_ids[$index] : null) : null;

            $addIssueProduct = new StockIssueProduct();
            $addIssueProduct->stock_issue_id = $stockIssueId;
            $addIssueProduct->product_id = $productId;
            $addIssueProduct->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $addIssueProduct->warehouse_id = $warehouse_id;
            $addIssueProduct->quantity = $request->quantities[$index];
            $addIssueProduct->unit_id = $request->unit_ids[$index];
            $addIssueProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index] ? $request->unit_costs_inc_tax[$index] : 0;
            $addIssueProduct->subtotal = $request->subtotals[$index];
            $addIssueProduct->save();

            $index++;
        }
    }

    public function updateStockIssueProduct($request, $stockIssueId)
    {
        $index = 0;
        foreach ($request->product_ids as $productId) {

            $warehouse_id = isset($request->warehouse_ids) ? ($request->warehouse_ids[$index] ? $request->warehouse_ids[$index] : null) : null;

            $addOrUpdateIssueProduct = '';
            $issuedProduct = StockIssueProduct::where('id', $request->stock_issue_product_ids[$index])->first();

            if ($issuedProduct) {

                $addOrUpdateIssueProduct = $issuedProduct;
            } else {

                $addOrUpdateIssueProduct = new StockIssueProduct();
            }

            $addOrUpdateIssueProduct->stock_issue_id = $stockIssueId;
            $addOrUpdateIssueProduct->product_id = $productId;
            $addOrUpdateIssueProduct->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $addOrUpdateIssueProduct->warehouse_id = $warehouse_id;
            $addOrUpdateIssueProduct->quantity = $request->quantities[$index];
            $addOrUpdateIssueProduct->unit_id = $request->unit_ids[$index];
            $addOrUpdateIssueProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
            $addOrUpdateIssueProduct->subtotal = $request->subtotals[$index];
            $addOrUpdateIssueProduct->is_delete_in_update = 0;
            $addOrUpdateIssueProduct->save();

            $index++;
        }
    }
}
