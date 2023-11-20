<?php

namespace App\Utils;

use App\Models\Purchase;
use App\Models\PurchaseOrderProduct;
use Illuminate\Support\Facades\DB;

class PurchaseOrderProductUtil
{
    public function addPurchaseOrderProduct($request, $isEditProductPrice, $purchaseId)
    {
        $index = 0;
        foreach ($request->product_ids as $productId) {

            $addPurchaseProduct = new PurchaseOrderProduct();
            $addPurchaseProduct->purchase_id = $purchaseId;
            $addPurchaseProduct->product_id = $productId;
            $addPurchaseProduct->product_variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $addPurchaseProduct->description = $request->descriptions[$index];
            $addPurchaseProduct->order_quantity = $request->quantities[$index];
            $addPurchaseProduct->pending_quantity = $request->quantities[$index];
            $addPurchaseProduct->unit_id = $request->unit_ids[$index];
            $addPurchaseProduct->unit_cost = $request->unit_costs_exc_tax[$index];
            $addPurchaseProduct->unit_discount = $request->unit_discounts[$index];
            $addPurchaseProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
            $addPurchaseProduct->unit_discount_type = $request->unit_discount_types[$index];
            $addPurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
            $addPurchaseProduct->subtotal = $request->subtotals[$index];
            $addPurchaseProduct->tax_ac_id = $request->tax_ac_ids[$index];
            $addPurchaseProduct->tax_type = $request->tax_types[$index];
            $addPurchaseProduct->unit_tax_percent = $request->unit_tax_percents[$index];
            $addPurchaseProduct->unit_tax_amount = $request->unit_tax_amounts[$index];

            $addPurchaseProduct->net_unit_cost = $request->net_unit_costs[$index];
            $addPurchaseProduct->line_total = $request->linetotals[$index];

            if ($isEditProductPrice == '1') {

                $addPurchaseProduct->profit_margin = $request->profits[$index];
                $addPurchaseProduct->selling_price = $request->selling_prices[$index];
            }

            if (isset($request->lot_numbers)) {

                $addPurchaseProduct->lot_no = $request->lot_numbers[$index];
            }

            $addPurchaseProduct->save();
            $index++;
        }
    }

    public function updatePurchaseOrderProduct($request, $orderId, $isEditProductPrice, $index)
    {
        $filterVariantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $purchaseOrderedProduct = PurchaseOrderProduct::where('purchase_id', $orderId)
            ->where('id', $request->purchase_product_ids[$index])
            ->first();

        $addOrUpdatePurchaseProduct = '';
        if ($purchaseOrderedProduct) {

            $addOrUpdatePurchaseProduct = $purchaseOrderedProduct;
        } else {

            $addOrUpdatePurchaseProduct = new PurchaseOrderProduct();
        }

        $addOrUpdatePurchaseProduct->purchase_id = $orderId;
        $addOrUpdatePurchaseProduct->product_id = $request->product_ids[$index];
        $addOrUpdatePurchaseProduct->product_variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addOrUpdatePurchaseProduct->description = $request->descriptions[$index];
        $addOrUpdatePurchaseProduct->order_quantity = $request->quantities[$index];
        $addOrUpdatePurchaseProduct->pending_quantity = $request->quantities[$index];
        $addOrUpdatePurchaseProduct->unit_id = $request->unit_ids[$index];
        $addOrUpdatePurchaseProduct->unit_cost = $request->unit_costs_exc_tax[$index];
        $addOrUpdatePurchaseProduct->unit_discount = $request->unit_discounts[$index];
        $addOrUpdatePurchaseProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addOrUpdatePurchaseProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addOrUpdatePurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
        $addOrUpdatePurchaseProduct->subtotal = $request->subtotals[$index];
        $addOrUpdatePurchaseProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrUpdatePurchaseProduct->tax_type = $request->tax_types[$index];
        $addOrUpdatePurchaseProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addOrUpdatePurchaseProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addOrUpdatePurchaseProduct->net_unit_cost = $request->net_unit_costs[$index];
        $addOrUpdatePurchaseProduct->line_total = $request->linetotals[$index];
        $addOrUpdatePurchaseProduct->delete_in_update = 0;

        if ($isEditProductPrice == '1') {

            $addOrUpdatePurchaseProduct->profit_margin = $request->profits[$index];
            $addOrUpdatePurchaseProduct->selling_price = $request->selling_prices[$index];
        }

        if (isset($request->lot_numbers)) {

            $addOrUpdatePurchaseProduct->lot_no = $request->lot_numbers[$index];
        }

        $addOrUpdatePurchaseProduct->save();

        return $addOrUpdatePurchaseProduct;
    }

    public function adjustPurchaseOrderProductPendingQty($orderId)
    {
        $order = Purchase::with(['orderedProducts'])->where('id', $orderId)->first();
        foreach ($order->orderedProducts as $orderedProduct) {

            $received = DB::table('receive_stock_products')
                ->where('receive_stocks.purchase_order_id', $order->id)
                ->leftJoin('receive_stocks', 'receive_stock_products.receive_stock_id', 'receive_stocks.id')
                ->where('receive_stock_products.product_id', $orderedProduct->product_id)
                ->where('receive_stock_products.variant_id', $orderedProduct->product_variant_id)
                ->select(DB::raw('SUM(receive_stock_products.quantity) as received_qty'))
                ->groupBy('receive_stocks.purchase_order_id')
                ->get();

            $pendingQty = $orderedProduct->order_quantity - $received->sum('received_qty');
            $orderedProduct->received_quantity = $received->sum('received_qty');
            $orderedProduct->pending_quantity = $pendingQty;
            $orderedProduct->save();
        }
    }
}
