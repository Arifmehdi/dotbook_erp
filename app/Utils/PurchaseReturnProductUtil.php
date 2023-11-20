<?php

namespace App\Utils;

use App\Models\PurchaseReturnProduct;

class PurchaseReturnProductUtil
{
    public function addPurchaseReturnProduct($purchaseReturnId, $request, $index)
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $addPurchaseReturnProduct = new PurchaseReturnProduct();
        $addPurchaseReturnProduct->purchase_return_id = $purchaseReturnId;
        $addPurchaseReturnProduct->purchase_product_id = $request->purchase_product_ids[$index];
        $addPurchaseReturnProduct->product_id = $request->product_ids[$index];
        $addPurchaseReturnProduct->product_variant_id = $variant_id;
        $addPurchaseReturnProduct->warehouse_id = $request->warehouse_ids[$index];
        $addPurchaseReturnProduct->return_qty = $request->return_quantities[$index];
        $addPurchaseReturnProduct->purchase_qty = $request->purchased_quantities[$index];
        $addPurchaseReturnProduct->unit_id = $request->unit_ids[$index];
        $addPurchaseReturnProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
        $addPurchaseReturnProduct->unit_discount = $request->unit_discounts[$index];
        $addPurchaseReturnProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addPurchaseReturnProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addPurchaseReturnProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addPurchaseReturnProduct->tax_type = $request->tax_types[$index];
        $addPurchaseReturnProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addPurchaseReturnProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addPurchaseReturnProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addPurchaseReturnProduct->return_subtotal = $request->subtotals[$index];
        $addPurchaseReturnProduct->save();

        return $addPurchaseReturnProduct;
    }

    public function updatePurchaseReturnProduct($purchaseReturnId, $request, $index)
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $purchaseReturnProduct = PurchaseReturnProduct::where('purchase_return_id', $purchaseReturnId)
            ->where('id', $request->purchase_return_product_ids[$index])
            ->first();

        $addOrUpdatePurchaseReturnProduct = '';
        $currentUnitTaxAcId = $purchaseReturnProduct ? $purchaseReturnProduct->tax_ac_id : null;
        if ($purchaseReturnProduct) {

            $addOrUpdatePurchaseReturnProduct = $purchaseReturnProduct;
        } else {

            $addOrUpdatePurchaseReturnProduct = new PurchaseReturnProduct();
        }

        $addOrUpdatePurchaseReturnProduct->purchase_return_id = $purchaseReturnId;
        $addOrUpdatePurchaseReturnProduct->purchase_product_id = $request->purchase_product_ids[$index];
        $addOrUpdatePurchaseReturnProduct->product_id = $request->product_ids[$index];
        $addOrUpdatePurchaseReturnProduct->product_variant_id = $variant_id;
        $addOrUpdatePurchaseReturnProduct->warehouse_id = $request->warehouse_ids[$index];
        $addOrUpdatePurchaseReturnProduct->return_qty = $request->return_quantities[$index];
        $addOrUpdatePurchaseReturnProduct->purchase_qty = $request->purchased_quantities[$index];
        $addOrUpdatePurchaseReturnProduct->unit_id = $request->unit_ids[$index];
        $addOrUpdatePurchaseReturnProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
        $addOrUpdatePurchaseReturnProduct->unit_discount = $request->unit_discounts[$index];
        $addOrUpdatePurchaseReturnProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addOrUpdatePurchaseReturnProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addOrUpdatePurchaseReturnProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrUpdatePurchaseReturnProduct->tax_type = $request->tax_types[$index];
        $addOrUpdatePurchaseReturnProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addOrUpdatePurchaseReturnProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addOrUpdatePurchaseReturnProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrUpdatePurchaseReturnProduct->return_subtotal = $request->subtotals[$index];
        $addOrUpdatePurchaseReturnProduct->is_delete_in_update = 0;
        $addOrUpdatePurchaseReturnProduct->save();

        return ['addOrUpdatePurchaseReturnProduct' => $addOrUpdatePurchaseReturnProduct, 'currentUnitTaxAcId' => $currentUnitTaxAcId];
    }
}
