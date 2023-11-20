<?php

namespace App\Utils;

use App\Models\SaleProduct;

class DeliveryOrderProductUtil
{
    public function updateDeliveryOrderProduct($doId, $request, $index)
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $addOrUpdateDoProduct = '';

        $doProduct = SaleProduct::where('sale_id', $doId)->where('id', $request->do_product_ids[$index])->first();

        if ($doProduct) {

            $addOrUpdateDoProduct = $doProduct;
        } else {

            $addOrUpdateDoProduct = new SaleProduct();
        }

        $addOrUpdateDoProduct->sale_id = $doId;
        $addOrUpdateDoProduct->product_id = $request->product_ids[$index];
        $addOrUpdateDoProduct->product_variant_id = $variant_id;
        $addOrUpdateDoProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrUpdateDoProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addOrUpdateDoProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addOrUpdateDoProduct->price_type = $request->price_types[$index];
        $addOrUpdateDoProduct->pr_amount = $request->pr_amounts[$index];
        $addOrUpdateDoProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addOrUpdateDoProduct->unit_discount = $request->unit_discounts[$index];
        $addOrUpdateDoProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addOrUpdateDoProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrUpdateDoProduct->tax_type = $request->tax_types[$index];
        $addOrUpdateDoProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addOrUpdateDoProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addOrUpdateDoProduct->unit_id = $request->unit_ids[$index];
        $addOrUpdateDoProduct->subtotal = $request->subtotals[$index];
        $addOrUpdateDoProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
        $addOrUpdateDoProduct->ordered_quantity = $request->quantities[$index];
        $addOrUpdateDoProduct->do_qty = $request->quantities[$index];
        $addOrUpdateDoProduct->delete_in_update = 0;
        $addOrUpdateDoProduct->save();

        return $addOrUpdateDoProduct;
    }

    public function addDoSaleInvoiceProduct($request, $saleId, $index)
    {
        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $saleProduct = new SaleProduct();
        $saleProduct->sale_id = $saleId;
        $saleProduct->product_id = $request->product_ids[$index];
        $saleProduct->product_variant_id = $variantId;
        $saleProduct->quantity = $request->quantities[$index];
        $warehouse_id = $request->warehouse_ids[$index];
        $saleProduct->stock_warehouse_id = $warehouse_id;
        $saleProduct->unit_id = $request->unit_ids[$index];
        $saleProduct->unit_discount_type = $request->unit_discount_types[$index];
        $saleProduct->unit_discount = $request->unit_discounts[$index];
        $saleProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $saleProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $saleProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $saleProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $saleProduct->price_type = $request->price_types[$index];
        $saleProduct->pr_amount = $request->pr_amounts[$index];
        $saleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $saleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $saleProduct->unit_price_inc_tax = $request->unit_prices[$index];
        $saleProduct->subtotal = $request->subtotals[$index];
        $saleProduct->save();

        return $saleProduct;
    }
}
