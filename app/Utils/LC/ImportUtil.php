<?php

namespace App\Utils\LC;

use App\Models\LC\ImportProduct;

class ImportUtil
{
    public function addImportProduct($request, $ImportId)
    {
        $index = 0;
        foreach ($request->product_ids as $productId) {

            $addImportProduct = new ImportProduct();
            $addPurchaseProduct->import_id = $ImportId;
            $addPurchaseProduct->product_id = $productId;
            $addPurchaseProduct->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $addPurchaseProduct->description = $request->descriptions[$index];
            $addPurchaseProduct->quantity = $request->quantities[$index];
            $addPurchaseProduct->unit = $request->units[$index];
            $addPurchaseProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
            $addPurchaseProduct->unit_discount = $request->unit_discounts[$index];
            $addPurchaseProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
            $addPurchaseProduct->unit_discount_type = $request->unit_discount_types[$index];
            $addPurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
            $addPurchaseProduct->impo_subtotal = $request->subtotals[$index];
            $addPurchaseProduct->created_at = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
            $addPurchaseProduct->save();

            $index++;
        }
    }
}
