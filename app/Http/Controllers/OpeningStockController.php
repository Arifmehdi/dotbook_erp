<?php

namespace App\Http\Controllers;

use App\Utils\OpeningStockUtil;
use App\Utils\ProductStockUtil;
use App\Utils\PurchaseSaleChainUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OpeningStockController extends Controller
{
    public function __construct(
        private OpeningStockUtil $openingStockUtil,
        private ProductStockUtil $productStockUtil,
        private PurchaseSaleChainUtil $purchaseSaleChainUtil,
    ) {
    }

    public function createOrEdit($productId)
    {
        $product = DB::table('products')->where('id', $productId)->select('id', 'name', 'product_code')->first();

        $products = DB::table('products')->where('products.id', $productId)
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->select(
                'products.id as p_id',
                'products.name as p_name',
                'products.product_cost as p_cost',
                'products.product_cost_with_tax as p_cost_inc_tax',
                'units.code_name as u_code',
                'product_variants.id as v_id',
                'product_variants.variant_name as v_name',
                'product_variants.variant_cost as v_cost',
                'product_variants.variant_cost_with_tax as v_cost_inc_tax',
            )->get();

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        return view('inventories.products.opening_stock.create_or_edit_modal', compact('products', 'warehouses', 'product'));
    }

    //update opening stock
    public function saveAddOrUpdate(Request $request)
    {
        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id.*' => 'required'], ['warehouse_id.required' => 'Warehouse field is required.']);
        }

        try {

            DB::beginTransaction();

            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

                $warehouseId = isset($request->warehouse_count) ? $request->warehouse_ids[$index] : null;

                $addOrUpdateOpeningStock = $this->openingStockUtil->saveAddOrEditOpeningStock(
                    productId: $product_id,
                    variantId: $variant_id,
                    unitCostIncTax: $request->unit_costs_inc_tax[$index],
                    quantity: $request->quantities[$index],
                    subtotal: $request->subtotals[$index],
                    warehouseId: isset($warehouseId) ? $warehouseId : null
                );

                $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                    tranColName: 'opening_stock_id',
                    transId: $addOrUpdateOpeningStock->id,
                    productId: $addOrUpdateOpeningStock->product_id,
                    quantity: $addOrUpdateOpeningStock->quantity,
                    variantId: $addOrUpdateOpeningStock->product_variant_id,
                    unitCostIncTax: $addOrUpdateOpeningStock->unit_cost_inc_tax,
                    sellingPrice: 0,
                    subTotal: $addOrUpdateOpeningStock->subtotal,
                    createdAt: date('Y-m-d H:i:s'),
                );

                $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);

                if (isset($request->warehouse_count)) {

                    $this->productStockUtil->addWarehouseProduct($product_id, $variant_id, $warehouseId);
                    $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $warehouseId);
                    $this->productStockUtil->adjustBranchStock($product_id, $variant_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($product_id, $variant_id);
                }

                if ($addOrUpdateOpeningStock->previous_warehouse_id) {

                    if (isset($request->warehouse_count) && $warehouseId != $addOrUpdateOpeningStock->previous_warehouse_id) {

                        $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $addOrUpdateOpeningStock->previous_warehouse_id);
                    }
                }

                $index++;
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Successfully item opening stock is added');
    }
}
