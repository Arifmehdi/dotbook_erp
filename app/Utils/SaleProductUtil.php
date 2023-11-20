<?php

namespace App\Utils;

use App\Models\SaleProduct;

class SaleProductUtil
{
    public function addSaleProduct($saleId, $request, $index)
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $warehouse_id = isset($request->warehouse_ids) ? ($request->warehouse_ids[$index] ? $request->warehouse_ids[$index] : null) : null;

        $addSaleProduct = new SaleProduct();
        $addSaleProduct->sale_id = $saleId;
        $addSaleProduct->stock_warehouse_id = $warehouse_id;
        $addSaleProduct->product_id = $request->product_ids[$index];
        $addSaleProduct->product_variant_id = $variant_id;
        $addSaleProduct->quantity = $request->quantities[$index];
        $addSaleProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addSaleProduct->unit_discount = $request->unit_discounts[$index];
        $addSaleProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addSaleProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addSaleProduct->tax_type = $request->tax_types[$index];
        $addSaleProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addSaleProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addSaleProduct->unit_id = $request->unit_ids[$index];
        $addSaleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addSaleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addSaleProduct->price_type = $request->price_types[$index];
        $addSaleProduct->pr_amount = $request->pr_amounts[$index];
        $addSaleProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addSaleProduct->subtotal = $request->subtotals[$index];
        $addSaleProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
        $addSaleProduct->save();

        return $addSaleProduct;
    }

    public function updateSaleProduct($saleId, $request, $index)
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $warehouse_id = isset($request->warehouse_ids) ? ($request->warehouse_ids[$index] ? $request->warehouse_ids[$index] : null) : null;

        $addOrUpdateSaleProduct = '';

        $saleProduct = SaleProduct::with(['taxLedgerEntry'])->where('sale_id', $saleId)
            ->where('id', $request->sale_product_ids[$index])
            ->first();

        $currentTaxAcId = $saleProduct ? $saleProduct->tax_ac_id : null;

        if ($saleProduct) {

            $addOrUpdateSaleProduct = $saleProduct;

            if ($saleProduct->taxLedgerEntry) {

                $saleProduct->taxLedgerEntry->delete();
            }
        } else {

            $addOrUpdateSaleProduct = new SaleProduct();
        }

        $addOrUpdateSaleProduct->sale_id = $saleId;
        $addOrUpdateSaleProduct->stock_warehouse_id = $warehouse_id;
        $addOrUpdateSaleProduct->product_id = $request->product_ids[$index];
        $addOrUpdateSaleProduct->product_variant_id = $variant_id;
        $addOrUpdateSaleProduct->quantity = $request->quantities[$index];
        $addOrUpdateSaleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrUpdateSaleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addOrUpdateSaleProduct->price_type = $request->price_types[$index];
        $addOrUpdateSaleProduct->pr_amount = $request->pr_amounts[$index];
        $addOrUpdateSaleProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addOrUpdateSaleProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addOrUpdateSaleProduct->unit_discount = $request->unit_discounts[$index];
        $addOrUpdateSaleProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addOrUpdateSaleProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrUpdateSaleProduct->tax_type = $request->tax_types[$index];
        $addOrUpdateSaleProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addOrUpdateSaleProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addOrUpdateSaleProduct->unit_id = $request->unit_ids[$index];
        $addOrUpdateSaleProduct->subtotal = $request->subtotals[$index];
        $addOrUpdateSaleProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
        $addOrUpdateSaleProduct->delete_in_update = 0;
        $addOrUpdateSaleProduct->save();

        return ['addOrUpdateSaleProduct' => $addOrUpdateSaleProduct, 'currentTaxAcId' => $currentTaxAcId];
    }
}
