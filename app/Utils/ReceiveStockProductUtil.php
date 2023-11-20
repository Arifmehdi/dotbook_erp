<?php

namespace App\Utils;

use App\Models\ReceiveStockProduct;

class ReceiveStockProductUtil
{
    public function addReceiveStockProducts($request, $receiveStockId)
    {
        $index = 0;
        foreach ($request->product_ids as $productId) {

            $addReceiveStockProduct = new ReceiveStockProduct();
            $addReceiveStockProduct->receive_stock_id = $receiveStockId;
            $addReceiveStockProduct->product_id = $productId;
            $addReceiveStockProduct->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $addReceiveStockProduct->short_description = $request->short_descriptions[$index];
            $addReceiveStockProduct->quantity = $request->quantities[$index];
            $addReceiveStockProduct->unit_id = $request->unit_ids[$index];
            $addReceiveStockProduct->purchase_order_product_id = $request->purchase_order_product_ids[$index];

            if (isset($request->lot_numbers)) {

                $addReceiveStockProduct->lot_number = $request->lot_numbers[$index];
            }

            $addReceiveStockProduct->save();
            $index++;
        }
    }

    public function updateReceiveStockProducts($request, $receiveStock, $productStockUtil)
    {
        foreach ($receiveStock->receiveStockProducts as $receiveStockProduct) {

            $receiveStockProduct->is_delete_in_update = 1;
            $receiveStockProduct->save();
        }

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

            $addOrEditReceiveStockProduct = '';
            $updateReceiveStockProduct = ReceiveStockProduct::where('receive_stock_id', $receiveStock->id)
                ->where('id', $request->receive_stock_product_ids[$index])->first();

            if ($updateReceiveStockProduct) {

                $addOrEditReceiveStockProduct = $updateReceiveStockProduct;
            } else {

                $addOrEditReceiveStockProduct = new ReceiveStockProduct();
            }

            $addOrEditReceiveStockProduct->receive_stock_id = $receiveStock->id;
            $addOrEditReceiveStockProduct->product_id = $productId;
            $addOrEditReceiveStockProduct->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $addOrEditReceiveStockProduct->short_description = $request->short_descriptions[$index];
            $addOrEditReceiveStockProduct->quantity = $request->quantities[$index];
            $addOrEditReceiveStockProduct->unit_id = $request->unit_ids[$index];
            $addOrEditReceiveStockProduct->purchase_order_product_id = $request->purchase_order_product_ids[$index];
            $addOrEditReceiveStockProduct->is_delete_in_update = 0;

            if (isset($request->lot_numbers)) {

                $addOrEditReceiveStockProduct->lot_number = $request->lot_numbers[$index];
            }

            $addOrEditReceiveStockProduct->save();

            $index++;
        }

        // deleted not getting previous product
        $deletedUnusedReceiveStockProducts = ReceiveStockProduct::where('receive_stock_id', $receiveStock->id)
            ->where('is_delete_in_update', 1)
            ->get();

        if (count($deletedUnusedReceiveStockProducts) > 0) {

            foreach ($deletedUnusedReceiveStockProducts as $receiveStockProduct) {

                $receiveStockProduct->delete();
                // Adjust deleted product stock
                $productStockUtil->adjustMainProductAndVariantStock($receiveStockProduct->product_id, $receiveStockProduct->variant_id);

                if (isset($request->warehouse_count)) {

                    $productStockUtil->adjustWarehouseStock($receiveStockProduct->product_id, $receiveStockProduct->variant_id, $request->warehouse_id);
                } else {

                    $productStockUtil->adjustBranchStock($receiveStockProduct->product_id, $receiveStockProduct->variant_id);
                }
            }
        }
    }
}
