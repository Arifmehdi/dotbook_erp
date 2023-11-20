<?php

namespace App\Utils;

use App\Models\SaleProduct;

class SalesOrderProductUtil
{
    public function addSaleOrderProduct($orderId, $request, $index)
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $addOrderProduct = new SaleProduct();
        $addOrderProduct->sale_id = $orderId;
        $addOrderProduct->product_id = $request->product_ids[$index];
        $addOrderProduct->product_variant_id = $variant_id;
        $addOrderProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addOrderProduct->unit_discount = $request->unit_discounts[$index];
        $addOrderProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addOrderProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrderProduct->tax_type = $request->tax_types[$index];
        $addOrderProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addOrderProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addOrderProduct->unit_id = $request->unit_ids[$index];
        $addOrderProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrderProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addOrderProduct->price_type = $request->price_types[$index];
        $addOrderProduct->pr_amount = $request->pr_amounts[$index];
        $addOrderProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addOrderProduct->subtotal = $request->subtotals[$index];
        $addOrderProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
        $addOrderProduct->ordered_quantity = $request->quantities[$index];

        if ($request->status == 7) {

            $addOrderProduct->do_qty = $request->quantities[$index];
        }

        $addOrderProduct->save();

        return $addOrderProduct;
    }

    public function updateSaleOrderProduct($orderId, $request, $index)
    {
        $addOrUpdateOrderProduct = '';

        $orderProduct = SaleProduct::where('sale_id', $orderId)->where('id', $request->order_product_ids[$index])->first();
        if ($orderProduct) {

            $addOrUpdateOrderProduct = $orderProduct;
        } else {

            $addOrUpdateOrderProduct = new SaleProduct();
        }

        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addOrUpdateOrderProduct->sale_id = $orderId;
        $addOrUpdateOrderProduct->product_id = $request->product_ids[$index];
        $addOrUpdateOrderProduct->product_variant_id = $variant_id;
        $addOrUpdateOrderProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addOrUpdateOrderProduct->unit_discount = $request->unit_discounts[$index];
        $addOrUpdateOrderProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addOrUpdateOrderProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrUpdateOrderProduct->tax_type = $request->tax_types[$index];
        $addOrUpdateOrderProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addOrUpdateOrderProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addOrUpdateOrderProduct->unit_id = $request->unit_ids[$index];
        $addOrUpdateOrderProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrUpdateOrderProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addOrUpdateOrderProduct->price_type = $request->price_types[$index];
        $addOrUpdateOrderProduct->pr_amount = $request->pr_amounts[$index];
        $addOrUpdateOrderProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addOrUpdateOrderProduct->subtotal = $request->subtotals[$index];
        $addOrUpdateOrderProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
        $addOrUpdateOrderProduct->ordered_quantity = $request->quantities[$index];

        if ($request->status == 7) {

            $addOrUpdateOrderProduct->do_qty = $request->quantities[$index];
        }

        $addOrUpdateOrderProduct->delete_in_update = 0;
        $addOrUpdateOrderProduct->save();

        return $addOrUpdateOrderProduct;
    }
}
