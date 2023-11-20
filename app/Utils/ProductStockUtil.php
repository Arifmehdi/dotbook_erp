<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\ProductBranch;
use App\Models\ProductBranchVariant;
use App\Models\ProductVariant;
use App\Models\ProductWarehouse;
use App\Models\ProductWarehouseVariant;
use Illuminate\Support\Facades\DB;

class ProductStockUtil
{
    public function adjustMainProductAndVariantStock($product_id, $variant_id)
    {
        $product = DB::table('products')->where('id', $product_id)->select('id', 'is_manage_stock')->first();

        if ($product->is_manage_stock == 1) {

            $productOpeningStock = DB::table('product_opening_stocks')
                ->where('product_opening_stocks.product_id', $product_id)
                ->select(DB::raw('sum(quantity) as po_stock'))
                ->groupBy('product_opening_stocks.product_id')->get();

            $productPurchase = DB::table('purchase_products')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                ->where('purchases.receive_stock_id', null)
                ->where('purchase_products.product_id', $product_id)
                ->where('purchase_products.opening_stock_id', null)
                ->where('purchase_products.production_id', null)
                ->where('purchase_products.sale_return_product_id', null)
                ->where('purchase_products.daily_stock_product_id', null)
                ->select(DB::raw('sum(quantity) as total_purchase'))
                ->groupBy('purchase_products.product_id')->get();

            $productionQty = DB::table('productions')->where('productions.is_final', 1)
                ->where('productions.product_id', $product_id)
                ->select(DB::raw('sum(total_final_quantity) as total_quantity'))
                ->groupBy('productions.product_id')->get();

            $usedProductionQty = DB::table('production_ingredients')
                ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
                ->where('productions.is_final', 1)
                ->where('production_ingredients.product_id', $product_id)
                ->select(DB::raw('sum(input_qty) as total_quantity'))
                ->groupBy('production_ingredients.product_id')->get();

            $productSale = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->where('sale_products.product_id', $product_id)
                ->where('sales.status', 1)
                ->select(DB::raw('sum(quantity) as total_sale'))
                ->groupBy('sale_products.product_id')->get();

            $totalPurchaseReturn = DB::table('purchase_return_products')
                ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('product_id')->get();

            $totalSaleReturn = DB::table('sale_return_products')
                ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('product_id')->get();

            $adjustment = DB::table('stock_adjustment_products')
                ->where('stock_adjustment_products.product_id', $product_id)
                ->select(DB::raw('sum(quantity) as total_qty'))
                ->groupBy('stock_adjustment_products.product_id')->get();

            $stockIssueItem = DB::table('stock_issue_products')
                ->leftJoin('stock_issues', 'stock_issue_products.stock_issue_id', 'stock_issues.id')
                ->where('stock_issue_products.product_id', $product_id)
                ->select(DB::raw('sum(quantity) as total_stock_issue'))
                ->groupBy('stock_issue_products.product_id')->get();

            $dailyStock = DB::table('daily_stock_products')->where('product_id', $product_id)
                ->select(DB::raw('SUM(quantity) as total_daily_stock'))->groupBy('product_id')->get();

            $receivedStockByRequisition = DB::table('receive_stock_products')->where('product_id', $product_id)
                ->select(DB::raw('SUM(quantity) as total_receive_stock'))->groupBy('product_id')->get();

            $productCurrentStock = $productPurchase->sum('total_purchase')
                + $productOpeningStock->sum('po_stock')
                + $totalSaleReturn->sum('total_return')
                - $productSale->sum('total_sale')
                - $adjustment->sum('total_qty')
                - $totalPurchaseReturn->sum('total_return')
                + $productionQty->sum('total_quantity')
                + $receivedStockByRequisition->sum('total_receive_stock')
                - $usedProductionQty->sum('total_quantity')
                - $stockIssueItem->sum('total_stock_issue')
                + $dailyStock->sum('total_daily_stock');

            $product = Product::where('id', $product_id)->first();
            $product->quantity = $productCurrentStock;
            $product->number_of_sale = $productSale->sum('total_sale');
            $product->total_adjusted = $adjustment->sum('total_qty');
            $product->save();

            if ($variant_id) {

                $variantOpeningStock = DB::table('product_opening_stocks')
                    ->where('product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as vo_stock'))
                    ->groupBy('product_variant_id')->get();

                $variantPurchase = DB::table('purchase_products')
                    ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                    ->where('purchases.receive_stock_id', null)
                    ->where('purchase_products.product_variant_id', $variant_id)
                    ->where('purchase_products.opening_stock_id', null)
                    ->where('purchase_products.production_id', null)
                    ->where('purchase_products.sale_return_product_id', null)
                    ->where('purchase_products.daily_stock_product_id', null)
                    ->select(DB::raw('sum(quantity) as total_purchase'))
                    ->groupBy('purchase_products.product_variant_id')
                    ->get();

                $productionQty = DB::table('productions')->where('is_final', 1)
                    ->where('productions.product_id', $product_id)
                    ->where('productions.variant_id', $variant_id)
                    ->select(DB::raw('sum(total_final_quantity) as total_quantity'))
                    ->groupBy('productions.product_id')->get();

                $usedProductionQty = DB::table('production_ingredients')
                    ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
                    ->where('productions.is_final', 1)
                    ->where('production_ingredients.product_id', $product_id)
                    ->where('production_ingredients.variant_id', $variant_id)
                    ->select(DB::raw('sum(input_qty) as total_quantity'))
                    ->groupBy('production_ingredients.variant_id')->get();

                $variantSale = DB::table('sale_products')
                    ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                    ->where('sale_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as total_sale'))
                    ->groupBy('sale_products.product_variant_id')->get();

                $totalPurchaseReturn = DB::table('purchase_return_products')
                    ->where('product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('product_variant_id')->get();

                $totalSaleReturn = DB::table('sale_return_products')
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('product_variant_id')->get();

                $adjustment = DB::table('stock_adjustment_products')
                    ->where('stock_adjustment_products.product_id', $product_id)
                    ->where('stock_adjustment_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as total_qty'))
                    ->groupBy('stock_adjustment_products.product_variant_id')->get();

                $stockIssueItem = DB::table('stock_issue_products')
                    ->leftJoin('stock_issues', 'stock_issue_products.stock_issue_id', 'stock_issues.id')
                    ->where('stock_issue_products.product_id', $product_id)
                    ->where('stock_issue_products.variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as total_stock_issue'))
                    ->groupBy('stock_issue_products.variant_id')->get();

                $dailyStock = DB::table('daily_stock_products')
                    ->where('product_id', $product_id)
                    ->where('variant_id', $variant_id)
                    ->select(DB::raw('SUM(quantity) as total_daily_stock'))->groupBy('variant_id')->get();

                $receivedStockByRequisition = DB::table('receive_stock_products')
                    ->where('product_id', $product_id)
                    ->where('variant_id', $variant_id)
                    ->select(DB::raw('SUM(quantity) as total_receive_stock'))
                    ->groupBy('product_id')
                    ->groupBy('variant_id')
                    ->get();

                $variantCurrentStock = $variantPurchase->sum('total_purchase')
                    + $variantOpeningStock->sum('vo_stock')
                    + $totalSaleReturn->sum('total_return')
                    - $variantSale->sum('total_sale')
                    - $adjustment->sum('total_qty')
                    - $totalPurchaseReturn->sum('total_return')
                    + $productionQty->sum('total_quantity')
                    + $receivedStockByRequisition->sum('total_receive_stock')
                    - $usedProductionQty->sum('total_quantity')
                    - $stockIssueItem->sum('total_stock_issue')
                    + $dailyStock->sum('total_daily_stock');

                $variant = ProductVariant::where('id', $variant_id)->first();
                $variant->variant_quantity = $variantCurrentStock;
                $variant->number_of_sale = $variantSale->sum('total_sale');
                $variant->total_adjusted = $adjustment->sum('total_qty');
                $variant->save();
            }
        }
    }

    public function adjustBranchStock($product_id, $variant_id)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == 1) {

            $productOpeningStock = DB::table('product_opening_stocks')
                ->where('product_opening_stocks.warehouse_id', null)
                ->where('product_id', $product_id)
                ->select(DB::raw('sum(quantity) as po_stock'))
                ->groupBy('product_opening_stocks.product_id')->get();

            $productionQty = DB::table('productions')->where('is_final', 1)
                ->where('warehouse_id', null)
                ->where('productions.product_id', $product_id)
                ->select(DB::raw('sum(total_final_quantity) as total_quantity'))
                ->groupBy('productions.product_id')->get();

            $usedProductionQty = DB::table('production_ingredients')
                ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
                ->where('productions.is_final', 1)
                ->where('productions.warehouse_id', null)
                ->where('production_ingredients.product_id', $product_id)
                ->select(DB::raw('sum(input_qty) as total_quantity'))
                ->groupBy('production_ingredients.product_id')->get();

            $productSale = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->where('sale_products.stock_warehouse_id', null)
                ->where('sale_products.product_id', $product_id)
                ->where('sales.status', 1)
                ->select(DB::raw('sum(quantity) as total_sale'))
                ->groupBy('sale_products.product_id')->get();

            $productPurchase = DB::table('purchase_products')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                ->where('purchases.warehouse_id', null)
                ->where('purchases.receive_stock_id', null)
                ->where('purchase_products.product_id', $product_id)
                ->where('purchase_products.opening_stock_id', null)
                ->where('purchase_products.production_id', null)
                ->where('purchase_products.sale_return_product_id', null)
                ->where('purchase_products.daily_stock_product_id', null)
                ->select(DB::raw('sum(quantity) as total_purchase'))
                ->groupBy('purchase_products.product_id')->get();

            $stockIssueItem = DB::table('stock_issue_products')
                ->where('stock_issue_products.warehouse_id', null)
                ->where('stock_issue_products.product_id', $product_id)
                ->select(DB::raw('sum(quantity) as total_stock_issue'))
                ->groupBy('stock_issue_products.product_id')->get();

            $saleReturn = DB::table('sale_return_products')
                ->join('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
                ->where('sale_returns.warehouse_id', null)
                ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('sale_return_products.product_id')->get();

            $purchaseReturn = DB::table('purchase_return_products')
                ->where('purchase_return_products.warehouse_id', null)
                ->where('purchase_return_products.product_id', $product_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('purchase_return_products.product_id')->get();

            $transferred = DB::table('transfer_stock_to_warehouse_products')
                ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
                ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
                ->select(DB::raw('sum(received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_warehouse_products.product_id')->get();

            $received = DB::table('transfer_stock_to_branch_products')
                ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
                ->where('transfer_stock_to_branch_products.product_id', $product_id)
                ->select(DB::raw('sum(received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_branch_products.product_id')->get();

            $adjustment = DB::table('stock_adjustment_products')
                ->where('stock_adjustment_products.warehouse_id', null)
                ->where('stock_adjustment_products.product_id', $product_id)
                ->select(DB::raw('sum(quantity) as total_qty'))
                ->groupBy('stock_adjustment_products.product_id')->get();

            $dailyStock = DB::table('daily_stock_products')
                ->leftJoin('daily_stocks', 'daily_stock_products.daily_stock_id', 'daily_stocks.id')
                ->where('daily_stocks.warehouse_id', null)
                ->where('daily_stock_products.product_id', $product_id)
                ->select(DB::raw('SUM(quantity) as total_daily_stock'))->groupBy('daily_stock_products.product_id')->get();

            $receivedStockByRequisition = DB::table('receive_stock_products')
                ->where('product_id', $product_id)
                ->leftJoin('receive_stocks', 'receive_stock_products.receive_stock_id', 'receive_stocks.id')
                ->select(DB::raw('SUM(quantity) as total_received_stock'))
                ->where('receive_stocks.warehouse_id', null)
                ->groupBy('product_id')->get();

            $currentMbStock = $productOpeningStock->sum('po_stock')
                + $productPurchase->sum('total_purchase')
                - $productSale->sum('total_sale')
                + $saleReturn->sum('total_return')
                - $purchaseReturn->sum('total_return')
                - $transferred->sum('total_qty')
                - $adjustment->sum('total_qty')
                + $received->sum('total_qty')
                + $productionQty->sum('total_quantity')
                + $receivedStockByRequisition->sum('total_received_stock')
                - $usedProductionQty->sum('total_quantity')
                - $stockIssueItem->sum('total_stock_issue')
                + $dailyStock->sum('total_daily_stock');

            $productBranch = ProductBranch::where('product_id', $product_id)->first();
            $productBranch->product_quantity = $currentMbStock;
            $productBranch->total_sale = $productSale->sum('total_sale');
            $productBranch->total_purchased = $productPurchase->sum('total_purchase');
            $productBranch->total_adjusted = $adjustment->sum('total_qty');
            $productBranch->total_transferred = $transferred->sum('total_qty');
            $productBranch->total_received = $received->sum('total_qty');
            $productBranch->total_opening_stock = $productOpeningStock->sum('po_stock');
            $productBranch->total_sale_return = $saleReturn->sum('total_return');
            $productBranch->total_purchase_return = $purchaseReturn->sum('total_return');
            $productBranch->save();

            if ($variant_id) {

                $productOpeningStock = DB::table('product_opening_stocks')
                    ->where('product_opening_stocks.warehouse_id', null)
                    ->where('product_opening_stocks.product_id', $product_id)
                    ->where('product_opening_stocks.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as po_stock'))
                    ->groupBy('product_opening_stocks.product_variant_id')->get();

                $productionQty = DB::table('productions')->where('is_final', 1)
                    ->where('warehouse_id', null)
                    ->where('productions.product_id', $product_id)
                    ->where('productions.variant_id', $variant_id)
                    ->select(DB::raw('sum(total_final_quantity) as total_quantity'))
                    ->groupBy('productions.variant_id')->get();

                $usedProductionQty = DB::table('production_ingredients')
                    ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
                    ->where('productions.is_final', 1)
                    ->where('productions.warehouse_id', null)
                    ->where('production_ingredients.product_id', $product_id)
                    ->where('production_ingredients.variant_id', $variant_id)
                    ->select(DB::raw('sum(input_qty) as total_quantity'))
                    ->groupBy('production_ingredients.variant_id')->get();

                $productSale = DB::table('sale_products')
                    ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                    ->where('sale_products.stock_warehouse_id', null)
                    ->where('sale_products.product_id', $product_id)
                    ->where('sale_products.product_variant_id', $variant_id)
                    ->where('sales.status', 1)
                    ->select(DB::raw('sum(quantity) as total_sale'))
                    ->groupBy('sale_products.product_variant_id')->get();

                $productPurchase = DB::table('purchase_products')
                    ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                    ->where('purchases.warehouse_id', null)
                    ->where('purchases.receive_stock_id', null)
                    ->where('purchase_products.product_id', $product_id)
                    ->where('purchase_products.product_variant_id', $variant_id)
                    ->where('purchase_products.opening_stock_id', null)
                    ->where('purchase_products.production_id', null)
                    ->where('purchase_products.sale_return_product_id', null)
                    ->where('purchase_products.daily_stock_product_id', null)
                    ->select(DB::raw('sum(quantity) as total_purchase'))
                    ->groupBy('purchase_products.product_variant_id')->get();

                $stockIssueItem = DB::table('stock_issue_products')
                    ->where('stock_issue_products.warehouse_id', null)
                    ->where('stock_issue_products.product_id', $product_id)
                    ->where('stock_issue_products.variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as total_stock_issue'))
                    ->groupBy('stock_issue_products.variant_id')->get();

                $saleReturn = DB::table('sale_return_products')
                    ->join('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
                    ->where('sale_returns.warehouse_id', null)
                    ->where('sale_return_products.product_id', $product_id)
                    ->where('sale_return_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('sale_return_products.product_variant_id')->get();

                $purchaseReturn = DB::table('purchase_return_products')
                    ->where('purchase_return_products.warehouse_id', null)
                    ->where('purchase_return_products.product_id', $product_id)
                    ->where('purchase_return_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('purchase_return_products.product_id')
                    ->groupBy('purchase_return_products.product_variant_id')
                    ->get();

                $transferred = DB::table('transfer_stock_to_warehouse_products')
                    ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
                    ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
                    ->where('transfer_stock_to_warehouse_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(received_qty) as total_qty'))
                    ->groupBy('transfer_stock_to_warehouse_products.product_variant_id')->get();

                $received = DB::table('transfer_stock_to_branch_products')
                    ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
                    ->where('transfer_stock_to_branch_products.product_id', $product_id)
                    ->where('transfer_stock_to_branch_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(received_qty) as total_qty'))
                    ->groupBy('transfer_stock_to_branch_products.product_variant_id')->get();

                $adjustment = DB::table('stock_adjustment_products')
                    ->where('stock_adjustment_products.warehouse_id', null)
                    ->where('stock_adjustment_products.product_id', $product_id)
                    ->where('stock_adjustment_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as total_qty'))
                    ->groupBy('stock_adjustment_products.product_variant_id')->get();

                $dailyStock = DB::table('daily_stock_products')
                    ->leftJoin('daily_stocks', 'daily_stock_products.daily_stock_id', 'daily_stocks.id')
                    ->where('daily_stocks.warehouse_id', null)
                    ->where('daily_stock_products.product_id', $product_id)
                    ->where('daily_stock_products.variant_id', $variant_id)
                    ->select(DB::raw('SUM(quantity) as total_daily_stock'))->groupBy('daily_stock_products.variant_id')->get();

                $receivedStockByRequisition = DB::table('receive_stock_products')
                    ->where('receive_stock_products.product_id', $product_id)
                    ->where('receive_stock_products.variant_id', $variant_id)
                    ->leftJoin('receive_stocks', 'receive_stock_products.receive_stock_id', 'receive_stocks.id')
                    ->select(DB::raw('SUM(quantity) as total_received_stock'))
                    ->where('receive_stocks.warehouse_id', null)
                    ->groupBy('product_id')
                    ->groupBy('variant_id')
                    ->get();

                $currentMbStock = $productOpeningStock->sum('po_stock')
                    + $productPurchase->sum('total_purchase')
                    - $productSale->sum('total_sale')
                    + $saleReturn->sum('total_return')
                    - $purchaseReturn->sum('total_return')
                    - $transferred->sum('total_qty')
                    - $adjustment->sum('total_qty')
                    + $received->sum('total_qty')
                    + $productionQty->sum('total_quantity')
                    + $receivedStockByRequisition->sum('total_received_stock')
                    - $usedProductionQty->sum('total_quantity')
                    + $dailyStock->sum('total_daily_stock');

                $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)
                    ->first();

                $productBranchVariant->variant_quantity = $currentMbStock;
                $productBranchVariant->total_sale = $productSale->sum('total_sale');
                $productBranchVariant->total_purchased = $productPurchase->sum('total_purchase');
                $productBranchVariant->total_adjusted = $adjustment->sum('total_qty');
                $productBranchVariant->total_transferred = $transferred->sum('total_qty');
                $productBranchVariant->total_received = $received->sum('total_qty');
                $productBranchVariant->total_opening_stock = $productOpeningStock->sum('po_stock');
                $productBranchVariant->total_sale_return = $saleReturn->sum('total_return');
                $productBranchVariant->total_purchase_return = $purchaseReturn->sum('total_return');
                $productBranchVariant->save();
            }
        }
    }

    public function adjustWarehouseStock($product_id, $variant_id, $warehouse_id)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == 1) {

            $productOpeningStock = DB::table('product_opening_stocks')
                ->where('product_opening_stocks.warehouse_id', $warehouse_id)
                ->where('product_id', $product_id)
                ->select(DB::raw('sum(quantity) as po_stock'))
                ->groupBy('product_opening_stocks.product_id')->get();

            $productPurchase = DB::table('purchase_products')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                ->where('purchases.warehouse_id', $warehouse_id)
                ->where('purchases.receive_stock_id', null)
                ->where('purchase_products.product_id', $product_id)
                ->where('purchase_products.opening_stock_id', null)
                ->where('purchase_products.production_id', null)
                ->where('purchase_products.sale_return_product_id', null)
                ->where('purchase_products.daily_stock_product_id', null)
                ->select(DB::raw('sum(quantity) as total_purchase'))
                ->groupBy('purchase_products.product_id')->get();

            $stockIssueItem = DB::table('stock_issue_products')
                ->where('stock_issue_products.warehouse_id', $warehouse_id)
                ->where('stock_issue_products.product_id', $product_id)
                ->select(DB::raw('sum(quantity) as total_stock_issue'))
                ->groupBy('stock_issue_products.product_id')->get();

            $productSale = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->where('sale_products.stock_warehouse_id', $warehouse_id)
                ->where('sale_products.product_id', $product_id)
                ->where('sales.status', 1)
                ->select(DB::raw('sum(quantity) as total_sale'))
                ->groupBy('sale_products.product_id')->get();

            $productionQty = DB::table('productions')->where('is_final', 1)
                ->where('productions.warehouse_id', $warehouse_id)
                ->where('productions.product_id', $product_id)
                ->select(DB::raw('sum(total_final_quantity) as total_quantity'))
                ->groupBy('productions.product_id')->get();

            $usedProductionQty = DB::table('production_ingredients')
                ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
                ->where('productions.is_final', 1)
                ->where('productions.stock_warehouse_id', $warehouse_id)
                ->where('production_ingredients.product_id', $product_id)
                ->select(DB::raw('sum(input_qty) as total_quantity'))
                ->groupBy('production_ingredients.product_id')->get();

            $saleReturn = DB::table('sale_return_products')
                ->join('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
                ->where('sale_returns.warehouse_id', $warehouse_id)
                ->where('sale_return_products.product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('sale_return_products.product_id')->get();

            $purchaseReturn = DB::table('purchase_return_products')
                ->where('purchase_return_products.warehouse_id', $warehouse_id)
                ->where('purchase_return_products.product_id', $product_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('purchase_return_products.product_id')->get();

            $received = DB::table('transfer_stock_to_warehouse_products')
                ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
                ->where('transfer_stock_to_warehouses.warehouse_id', $warehouse_id)
                ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
                ->select(DB::raw('sum(received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_warehouse_products.product_id')->get();

            $transferred = DB::table('transfer_stock_to_branch_products')
                ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
                ->where('transfer_stock_to_branches.warehouse_id', $warehouse_id)
                ->where('transfer_stock_to_branch_products.product_id', $product_id)
                ->select(DB::raw('sum(received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_branch_products.product_id')->get();

            $adjustment = DB::table('stock_adjustment_products')
                ->where('stock_adjustment_products.warehouse_id', $warehouse_id)
                ->where('stock_adjustment_products.product_id', $product_id)
                ->select(DB::raw('sum(quantity) as total_qty'))
                ->groupBy('stock_adjustment_products.product_id')->get();

            $dailyStock = DB::table('daily_stock_products')
                ->leftJoin('daily_stocks', 'daily_stock_products.daily_stock_id', 'daily_stocks.id')
                ->where('daily_stocks.warehouse_id', $warehouse_id)
                ->where('daily_stock_products.product_id', $product_id)
                ->select(DB::raw('SUM(quantity) as total_daily_stock'))
                ->groupBy('daily_stock_products.product_id')->get();

            $receivedStockByRequisition = DB::table('receive_stock_products')
                ->where('receive_stock_products.product_id', $product_id)
                ->leftJoin('receive_stocks', 'receive_stock_products.receive_stock_id', 'receive_stocks.id')
                ->select(DB::raw('SUM(quantity) as total_received_stock'))
                ->where('receive_stocks.warehouse_id', $warehouse_id)
                ->groupBy('product_id')
                ->groupBy('variant_id')
                ->get();

            $currentMbStock = $productPurchase->sum('total_purchase')
                + $productOpeningStock->sum('po_stock')
                + $saleReturn->sum('total_return')
                - $productSale->sum('total_sale')
                - $purchaseReturn->sum('total_return')
                - $transferred->sum('total_qty')
                - $adjustment->sum('total_qty')
                + $received->sum('total_qty')
                + $productionQty->sum('total_quantity')
                + $receivedStockByRequisition->sum('total_received_stock')
                - $usedProductionQty->sum('total_quantity')
                - $stockIssueItem->sum('total_stock_issue')
                + $dailyStock->sum('total_daily_stock');

            $productWarehouse = ProductWarehouse::where('warehouse_id', $warehouse_id)->where('product_id', $product_id)->first();

            $productWarehouse->product_quantity = $currentMbStock;
            $productWarehouse->total_purchased = $productPurchase->sum('total_purchase');
            $productWarehouse->total_adjusted = $adjustment->sum('total_qty');
            $productWarehouse->total_transferred = $transferred->sum('total_qty');
            $productWarehouse->total_received = $received->sum('total_qty');
            $productWarehouse->save();

            if ($variant_id) {

                $productOpeningStock = DB::table('product_opening_stocks')
                    ->where('product_opening_stocks.warehouse_id', $warehouse_id)
                    ->where('product_opening_stocks.product_id', $product_id)
                    ->where('product_opening_stocks.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as po_stock'))
                    ->groupBy('product_opening_stocks.product_variant_id')->get();

                $productPurchase = DB::table('purchase_products')
                    ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                    ->where('purchases.receive_stock_id', null)
                    ->where('purchases.warehouse_id', $warehouse_id)
                    ->where('purchase_products.product_id', $product_id)
                    ->where('purchase_products.product_variant_id', $variant_id)
                    ->where('purchase_products.opening_stock_id', null)
                    ->where('purchase_products.production_id', null)
                    ->where('purchase_products.sale_return_product_id', null)
                    ->where('purchase_products.daily_stock_product_id', null)
                    ->select(DB::raw('sum(quantity) as total_purchase'))
                    ->groupBy('purchase_products.product_variant_id')->get();

                $stockIssueItem = DB::table('stock_issue_products')
                    ->where('stock_issue_products.warehouse_id', $warehouse_id)
                    ->where('stock_issue_products.product_id', $product_id)
                    ->where('stock_issue_products.variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as total_stock_issue'))
                    ->groupBy('stock_issue_products.variant_id')
                    ->get();

                $productSale = DB::table('sale_products')
                    ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                    ->where('sale_products.stock_warehouse_id', $warehouse_id)
                    ->where('sale_products.product_id', $product_id)
                    ->where('sale_products.product_variant_id', $variant_id)
                    ->where('sales.status', 1)
                    ->select(DB::raw('sum(quantity) as total_sale'))
                    ->groupBy('sale_products.product_variant_id')->get();

                $productionQty = DB::table('productions')->where('is_final', 1)
                    ->where('productions.warehouse_id', $warehouse_id)
                    ->where('productions.product_id', $product_id)
                    ->where('productions.variant_id', $variant_id)
                    ->select(DB::raw('sum(total_final_quantity) as total_quantity'))
                    ->groupBy('productions.variant_id')->get();

                $usedProductionQty = DB::table('production_ingredients')
                    ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
                    ->where('productions.is_final', 1)
                    ->where('productions.stock_warehouse_id', $warehouse_id)
                    ->where('production_ingredients.product_id', $product_id)
                    ->where('production_ingredients.variant_id', $variant_id)
                    ->select(DB::raw('sum(input_qty) as total_quantity'))
                    ->groupBy('production_ingredients.variant_id')->get();

                $saleReturn = DB::table('sale_return_products')
                    ->join('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
                    ->where('sale_returns.warehouse_id', $warehouse_id)
                    ->where('sale_return_products.product_id', $product_id)
                    ->where('sale_return_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('sale_return_products.product_id')
                    ->groupBy('sale_return_products.product_variant_id')
                    ->get();

                $purchaseReturn = DB::table('purchase_return_products')
                    ->where('purchase_return_products.warehouse_id', $warehouse_id)
                    ->where('purchase_return_products.product_id', $product_id)
                    ->where('purchase_return_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('purchase_return_products.product_id')
                    ->groupBy('purchase_return_products.product_variant_id')
                    ->get();

                $received = DB::table('transfer_stock_to_warehouse_products')
                    ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
                    ->where('transfer_stock_to_warehouses.warehouse_id', $warehouse_id)
                    ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
                    ->where('transfer_stock_to_warehouse_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(received_qty) as total_qty'))
                    ->groupBy('transfer_stock_to_warehouse_products.product_variant_id')->get();

                $transferred = DB::table('transfer_stock_to_branch_products')
                    ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
                    ->where('transfer_stock_to_branches.warehouse_id', $warehouse_id)
                    ->where('transfer_stock_to_branch_products.product_id', $product_id)
                    ->where('transfer_stock_to_branch_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(received_qty) as total_qty'))
                    ->groupBy('transfer_stock_to_branch_products.product_variant_id')->get();

                $adjustment = DB::table('stock_adjustment_products')
                    ->where('stock_adjustment_products.warehouse_id', $warehouse_id)
                    ->where('stock_adjustment_products.product_id', $product_id)
                    ->where('stock_adjustment_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as total_qty'))
                    ->groupBy('stock_adjustment_products.product_variant_id')->get();

                $dailyStock = DB::table('daily_stock_products')
                    ->leftJoin('daily_stocks', 'daily_stock_products.daily_stock_id', 'daily_stocks.id')
                    ->where('daily_stocks.warehouse_id', $warehouse_id)
                    ->where('daily_stock_products.product_id', $product_id)
                    ->where('daily_stock_products.variant_id', $variant_id)
                    ->select(DB::raw('SUM(quantity) as total_daily_stock'))
                    ->groupBy('daily_stock_products.variant_id')->get();

                $receivedStockByRequisition = DB::table('receive_stock_products')
                    ->where('receive_stock_products.product_id', $product_id)
                    ->where('receive_stock_products.variant_id', $variant_id)
                    ->leftJoin('receive_stocks', 'receive_stock_products.receive_stock_id', 'receive_stocks.id')
                    ->select(DB::raw('SUM(quantity) as total_received_stock'))
                    ->where('receive_stocks.warehouse_id', $warehouse_id)
                    ->groupBy('product_id')
                    ->groupBy('variant_id')
                    ->get();

                $currentMbStock = $productPurchase->sum('total_purchase')
                    + $productOpeningStock->sum('po_stock')
                    + $saleReturn->sum('total_return')
                    - $productSale->sum('total_sale')
                    - $purchaseReturn->sum('total_return')
                    - $transferred->sum('total_qty')
                    - $adjustment->sum('total_qty')
                    + $received->sum('total_qty')
                    + $productionQty->sum('total_quantity')
                    + $receivedStockByRequisition->sum('total_received_stock')
                    - $usedProductionQty->sum('total_quantity')
                    - $stockIssueItem->sum('total_stock_issue')
                    + $dailyStock->sum('total_daily_stock');

                $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)
                    ->first();

                $productWarehouseVariant->variant_quantity = $currentMbStock;
                $productWarehouseVariant->total_purchased = $productPurchase->sum('total_purchase');
                $productWarehouseVariant->total_adjusted = $adjustment->sum('total_qty');
                $productWarehouseVariant->total_transferred = $transferred->sum('total_qty');
                $productWarehouseVariant->total_received = $received->sum('total_qty');
                $productWarehouseVariant->save();
            }
        }
    }

    public function addWarehouseProduct($product_id, $variant_id, $warehouse_id)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == 1) {

            $checkExistsProductInWarehouse = DB::table('product_warehouses')
                ->where('warehouse_id', $warehouse_id)
                ->where('product_id', $product_id)->first();

            if ($checkExistsProductInWarehouse) {

                if ($variant_id) {

                    $checkVariantInWarehouse = DB::table('product_warehouse_variants')
                        ->where('product_warehouse_id', $checkExistsProductInWarehouse->id)
                        ->where('product_id', $product_id)
                        ->where('product_variant_id', $variant_id)
                        ->first();

                    if (! $checkVariantInWarehouse) {

                        $productWarehouseVariant = new ProductWarehouseVariant();
                        $productWarehouseVariant->product_warehouse_id = $checkExistsProductInWarehouse->id;
                        $productWarehouseVariant->product_id = $product_id;
                        $productWarehouseVariant->product_variant_id = $variant_id;
                        $productWarehouseVariant->save();
                    }
                }
            } else {

                $productWarehouse = new ProductWarehouse();
                $productWarehouse->warehouse_id = $warehouse_id;
                $productWarehouse->product_id = $product_id;
                $productWarehouse->save();

                if ($variant_id) {
                    $productWarehouseVariant = new ProductWarehouseVariant();
                    $productWarehouseVariant->product_warehouse_id = $productWarehouse->id;
                    $productWarehouseVariant->product_id = $product_id;
                    $productWarehouseVariant->product_variant_id = $variant_id;
                    $productWarehouseVariant->save();
                }
            }
        }
    }

    public function addBranchProduct($product_id, $variant_id, $force_add = 0)
    {
        $product = DB::table('products')->where('id', $product_id)->select('id', 'is_manage_stock')->first();

        if ($product->is_manage_stock == 1 || ($product->is_manage_stock == 0 && $force_add == 1)) {

            $checkExistsProductInBranch = DB::table('product_branches')->where('product_id', $product_id)->first();

            if ($checkExistsProductInBranch) {

                if ($variant_id) {

                    $checkVariantInBranch = DB::table('product_branch_variants')
                        ->where('product_branch_id', $checkExistsProductInBranch->id)
                        ->where('product_id', $product_id)
                        ->where('product_variant_id', $variant_id)
                        ->first();

                    if (! $checkVariantInBranch) {

                        $productBranchVariant = new ProductBranchVariant();
                        $productBranchVariant->product_branch_id = $checkExistsProductInBranch->id;
                        $productBranchVariant->product_id = $product_id;
                        $productBranchVariant->product_variant_id = $variant_id;
                        $productBranchVariant->save();
                    }
                }
            } else {

                $productBranch = new ProductBranch();
                $productBranch->product_id = $product_id;
                $productBranch->save();

                if ($variant_id) {

                    $productBranchVariant = new ProductBranchVariant();
                    $productBranchVariant->product_branch_id = $productBranch->id;
                    $productBranchVariant->product_id = $product_id;
                    $productBranchVariant->product_variant_id = $variant_id;
                    $productBranchVariant->save();
                }
            }
        }
    }
}
