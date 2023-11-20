<?php

namespace App\Utils;

use App\Models\SaleProduct;

class QuotationProductUtil
{
    public function addQuotationProduct($quotationId, $request, $index)
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $addQuotationProduct = new SaleProduct();
        $addQuotationProduct->sale_id = $quotationId;
        $addQuotationProduct->product_id = $request->product_ids[$index];
        $addQuotationProduct->product_variant_id = $variant_id;
        $addQuotationProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addQuotationProduct->unit_discount = $request->unit_discounts[$index];
        $addQuotationProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addQuotationProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addQuotationProduct->tax_type = $request->tax_types[$index];
        $addQuotationProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addQuotationProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addQuotationProduct->unit_id = $request->unit_ids[$index];
        $addQuotationProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addQuotationProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addQuotationProduct->price_type = $request->price_types[$index];
        $addQuotationProduct->pr_amount = $request->pr_amounts[$index];
        $addQuotationProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addQuotationProduct->subtotal = $request->subtotals[$index];
        $addQuotationProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
        $addQuotationProduct->ordered_quantity = $request->quantities[$index];
        $addQuotationProduct->save();
    }

    public function updateQuotationProduct($quotationId, $request, $index)
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $addOrUpdateQuotationProduct = '';
        $quotationProduct = SaleProduct::where('sale_id', $quotationId)->where('id', $request->quotation_product_ids[$index])->first();

        if ($quotationProduct) {

            $addOrUpdateQuotationProduct = $quotationProduct;
        } else {

            $addOrUpdateQuotationProduct = new SaleProduct();
        }

        $addOrUpdateQuotationProduct->sale_id = $quotationId;
        $addOrUpdateQuotationProduct->product_id = $request->product_ids[$index];
        $addOrUpdateQuotationProduct->product_variant_id = $variant_id;
        $addOrUpdateQuotationProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addOrUpdateQuotationProduct->unit_discount = $request->unit_discounts[$index];
        $addOrUpdateQuotationProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addOrUpdateQuotationProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrUpdateQuotationProduct->tax_type = $request->tax_types[$index];
        $addOrUpdateQuotationProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addOrUpdateQuotationProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addOrUpdateQuotationProduct->unit_id = $request->unit_ids[$index];
        $addOrUpdateQuotationProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrUpdateQuotationProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addOrUpdateQuotationProduct->price_type = $request->price_types[$index];
        $addOrUpdateQuotationProduct->pr_amount = $request->pr_amounts[$index];
        $addOrUpdateQuotationProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addOrUpdateQuotationProduct->subtotal = $request->subtotals[$index];
        $addOrUpdateQuotationProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
        $addOrUpdateQuotationProduct->ordered_quantity = $request->quantities[$index];
        $addOrUpdateQuotationProduct->delete_in_update = 0;
        $addOrUpdateQuotationProduct->save();

        return $addOrUpdateQuotationProduct;
    }
}
