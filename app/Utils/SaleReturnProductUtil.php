<?php

namespace App\Utils;

use App\Models\SaleReturnProduct;

class SaleReturnProductUtil
{
    public function addReturnProduct($saleReturnId, $request, $index)
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $addReturnProduct = new SaleReturnProduct();
        $addReturnProduct->sale_return_id = $saleReturnId;
        $addReturnProduct->sale_product_id = $request->sale_product_ids[$index];
        $addReturnProduct->product_id = $request->product_ids[$index];
        $addReturnProduct->product_variant_id = $variant_id;
        $addReturnProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addReturnProduct->tax_type = $request->tax_types[$index];
        $addReturnProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addReturnProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addReturnProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addReturnProduct->unit_discount = $request->unit_discounts[$index];
        $addReturnProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addReturnProduct->sold_quantity = $request->sold_quantities[$index];
        $addReturnProduct->return_qty = $request->return_quantities[$index];
        $addReturnProduct->unit_id = $request->unit_ids[$index];
        $addReturnProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addReturnProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addReturnProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addReturnProduct->price_type = $request->price_types[$index];
        $addReturnProduct->pr_amount = $request->pr_amounts[$index];
        $addReturnProduct->return_subtotal = $request->subtotals[$index];
        $addReturnProduct->save();

        return $addReturnProduct;
    }

    public function updateReturnProduct($returnId, $request, $index)
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $addOrEditReturnProduct = '';

        $saleReturnProduct = SaleReturnProduct::where('sale_return_id', $returnId)
            ->where('id', $request->sale_return_product_ids[$index])->first();

        $currentTaxAcId = $saleReturnProduct ? $saleReturnProduct->tax_ac_id : null;

        if ($saleReturnProduct) {

            $addOrEditReturnProduct = $saleReturnProduct;
        } else {

            $addOrEditReturnProduct = new saleReturnProduct();
        }

        $addOrEditReturnProduct->sale_return_id = $returnId;
        $addOrEditReturnProduct->sale_product_id = $request->sale_product_ids[$index];
        $addOrEditReturnProduct->product_id = $request->product_ids[$index];
        $addOrEditReturnProduct->product_variant_id = $variant_id;
        $addOrEditReturnProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrEditReturnProduct->tax_type = $request->tax_types[$index];
        $addOrEditReturnProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addOrEditReturnProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addOrEditReturnProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addOrEditReturnProduct->unit_discount = $request->unit_discounts[$index];
        $addOrEditReturnProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addOrEditReturnProduct->sold_quantity = $request->sold_quantities[$index];
        $addOrEditReturnProduct->return_qty = $request->return_quantities[$index];
        $addOrEditReturnProduct->unit_id = $request->unit_ids[$index];
        $addOrEditReturnProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrEditReturnProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addOrEditReturnProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addOrEditReturnProduct->price_type = $request->price_types[$index];
        $addOrEditReturnProduct->pr_amount = $request->pr_amounts[$index];
        $addOrEditReturnProduct->return_subtotal = $request->subtotals[$index];
        $addOrEditReturnProduct->is_delete_in_update = 0;
        $addOrEditReturnProduct->save();

        return ['addOrEditReturnProduct' => $addOrEditReturnProduct, 'currentTaxAcId' => $currentTaxAcId];
    }
}
