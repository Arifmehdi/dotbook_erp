<?php

namespace App\Utils;

use App\Models\PurchaseRequisitionProduct;
use Illuminate\Support\Facades\DB;

class RequisitionProductUtil
{
    public function addRequisitionProduct($request, $requisitionId, $index)
    {
        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $addRequisitionProduct = new PurchaseRequisitionProduct();

        $productLastPurchaseDate = DB::table('purchase_products')
            ->where('product_id', $request->product_ids[$index])
            ->where('product_variant_id', $variantId)
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->select('purchases.date as last_purchase_date')
            ->orderBy('purchases.report_date', 'DESC')->first();

        $productLastPurchaseDateOn = $productLastPurchaseDate ? $productLastPurchaseDate->last_purchase_date : null;

        $addRequisitionProduct->requisition_id = $requisitionId;
        $addRequisitionProduct->product_id = $request->product_ids[$index];
        $addRequisitionProduct->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addRequisitionProduct->last_purchase_price = $request->last_unit_costs[$index];
        $addRequisitionProduct->last_purchase_price_on = $productLastPurchaseDateOn;
        $addRequisitionProduct->current_stock = $request->current_stocks[$index];
        $addRequisitionProduct->quantity = $request->quantities[$index];
        $addRequisitionProduct->left_qty = $request->quantities[$index];
        $addRequisitionProduct->unit_id = $request->unit_ids[$index];
        $addRequisitionProduct->purpose = $request->purposes[$index];
        $addRequisitionProduct->pr_type = $request->pr_types[$index];
        $addRequisitionProduct->save();

        return $addRequisitionProduct;
    }

    public function updateRequisitionProduct($requisitionId, $request, $index)
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $updateRequisitionProduct = PurchaseRequisitionProduct::where('requisition_id', $requisitionId)
            ->where('id', $request->requisition_product_ids[$index])->first();

        $addOrUpdateRequisitionProduct = '';

        if ($updateRequisitionProduct) {

            $addOrUpdateRequisitionProduct = $updateRequisitionProduct;
        } else {

            $addOrUpdateRequisitionProduct = new PurchaseRequisitionProduct();
        }

        $addOrUpdateRequisitionProduct->requisition_id = $requisitionId;
        $addOrUpdateRequisitionProduct->product_id = $request->product_ids[$index];
        $addOrUpdateRequisitionProduct->variant_id = $variant_id;
        $addOrUpdateRequisitionProduct->last_purchase_price = $request->last_unit_costs[$index];
        $addOrUpdateRequisitionProduct->current_stock = $request->current_stocks[$index];
        $addOrUpdateRequisitionProduct->quantity = $request->quantities[$index];
        $addOrUpdateRequisitionProduct->unit_id = $request->unit_ids[$index];
        $addOrUpdateRequisitionProduct->purpose = $request->purposes[$index];
        $addOrUpdateRequisitionProduct->pr_type = $request->pr_types[$index];
        $addOrUpdateRequisitionProduct->is_delete_in_update = 0;
        $addOrUpdateRequisitionProduct->save();

        return $addOrUpdateRequisitionProduct;
    }
}
