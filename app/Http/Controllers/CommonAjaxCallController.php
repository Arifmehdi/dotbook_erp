<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Product;
use App\Models\ProductBranch;
use App\Models\ProductWarehouse;
use App\Models\PurchaseByScaleWeight;
use App\Models\PurchaseOrderProduct;
use App\Models\PurchaseProduct;
use App\Models\PurchaseRequisitionProduct;
use App\Models\ReceiveStockProduct;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Utils\ProductUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class CommonAjaxCallController extends Controller
{

    public function __construct(private ProductUtil $productUtil)
    {
    }

    public function searchAccount($keyword, $onlyType)
    {
        $replacedStr = str_replace('~', '/', $keyword);
        $replacedStr = str_replace('^^^', '#', $replacedStr);
        $__keyword = $keyword == 'NULL' ? '' : $replacedStr;
        $accounts = '';

        $query = DB::table('accounts');
        if ($onlyType == 'bank_or_cash_accounts') {

            $query->where('account_groups.is_bank_or_cash_ac', 1);
        }

        if ($onlyType == 'expense_account') {

            $query->whereIn('account_groups.sub_group_number', [10, 11]);
        }

        $query->where('accounts.name', 'LIKE', '%' . $__keyword . '%');

        if ($onlyType == 'all' || $onlyType == 'bank_or_cash_accounts') {

            $query->orWhere('accounts.account_number', 'LIKE', '%' . $__keyword . '%');
        }

        $query->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id');

        $accounts = $query->select(
            'accounts.id',
            'accounts.name',
            'accounts.customer_id',
            'accounts.supplier_id',
            'accounts.account_number',
            'account_groups.name as group_name',
            'account_groups.main_group_number',
            'account_groups.sub_group_number',
            'account_groups.sub_sub_group_number',
        )->limit(150)->get();

        return $accounts;
    }

    public function searchCostCentre($keyword, $onlyType)
    {
        $__keyword = $keyword == 'NULL' ? '' : str_replace('~', '/', $keyword);
        $costCentres = '';

        $query = DB::table('cost_centres')
            ->where('cost_centres.name', 'LIKE', '%' . $__keyword . '%')
            ->leftJoin('cost_centre_categories', 'cost_centres.category_id', 'cost_centre_categories.id');

        if ($onlyType == 'expense') {

            $query->where('cost_centre_categories.use_in_expense_items', 1);
        } elseif ($onlyType == 'income') {

            $query->where('cost_centre_categories.use_in_income_items', 1);
        }

        $costCentres = $query->select(
            'cost_centres.id',
            'cost_centres.name',
            'cost_centre_categories.name as category_name',
        )->get();

        return $costCentres;
    }

    public function invoiceSearchList($invoiceId)
    {
        $invoices = DB::table('sales')->where('sales.status', 1)
            ->where('sales.invoice_id', 'like', "%$invoiceId%")
            ->select('sales.id', 'sales.invoice_id', 'sales.customer_account_id', 'sales.sale_account_id', 'sales.sr_user_id', 'sales.all_price_type')
            ->limit(50)->orderBy('sales.report_date', 'desc')->get();

        return view('common_ajax_view.invoice_search_list', compact('invoices'));
    }

    public function getSaleProducts($id)
    {
        // return DB::table('sale_products')
        //     ->where('sale_products.sale_id', $id)
        //     ->leftJoin('products', 'sale_products.product_id', 'products.id')
        //     ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
        //     ->select('sale_products.*', 'products.id as product_id', 'products.name as product_name', 'products.product_cost_with_tax', 'products.is_manage_stock', 'products.tax_type', 'products.product_code', 'product_variants.variant_name', 'product_variants.variant_code', 'product_variants.variant_cost_with_tax')->get();

        return SaleProduct::with([
            'saleUnit:id,name,base_unit_multiplier',
            'product:id,name,product_cost_with_tax,is_manage_stock,tax_type,product_code,unit_id',
            'product.unit:id,name,code_name',
            'product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'variant:id,variant_name,variant_code,variant_cost_with_tax',
        ])->where('sale_id', $id)->get();
    }

    public function categorySubcategories($categoryId)
    {
        return DB::table('categories')->where('parent_category_id', $categoryId)->select('id', 'name')->get();
    }

    public function getLastId($table, $placeholderLimit)
    {
        $invoiceVoucherRefIdUtil = new \App\Utils\InvoiceVoucherRefIdUtil();

        return str_pad($invoiceVoucherRefIdUtil->getLastId($table), $placeholderLimit, '0', STR_PAD_LEFT);
    }

    public function onlySearchProductForReports($product_name)
    {
        $products = DB::table('product_branches')
            ->where('name', 'like', "%{$product_name}%")
            ->orWhere('product_code', 'like', "%{$product_name}%")
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->select(
                'products.id as product_id',
                'products.name',
                'products.product_code',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
            )->limit(25)->get();

        if (count($products) > 0) {

            return view('common_ajax_view.search_result', compact('products'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    public function searchSaleDoIds($key_word)
    {
        $dos = DB::table('sales')
            ->where('sales.do_status', 1)->where('sales.do_id', 'like', "%{$key_word}%")
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->select('sales.id', 'sales.invoice_id', 'sales.date', 'sales.customer_account_id', 'sales.order_discount_type', 'sales.expire_date', 'sales.order_discount', 'sales.total_item', 'sales.order_tax_percent', 'sales.net_total_amount', 'sales.total_payable_amount', 'sales.paid', 'sales.due', 'sales.sale_note', 'sales.shipping_address', 'sales.receiver_phone', 'sales.shipment_charge', 'sales.invoice_id', 'sales.do_id', 'sales.sale_account_id', 'customers.name as cmr_name')->get();

        if (count($dos) > 0) {

            return view('common_ajax_view.do_ids_search_result_list', compact('dos'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    public function searchPurchaseByWeightChallanList($key_word)
    {
        $purchaseBySales = DB::table('purchase_by_scales')
            ->where('purchase_by_scales.status')
            ->where('purchase_by_scales.challan_no', 'like', "%{$key_word}%")
            ->orWhere('purchase_by_scales.vehicle_number', 'like', "%{$key_word}%")
            ->orWhere('purchase_by_scales.voucher_no', 'like', "%{$key_word}%")
            ->orderBy('purchase_by_scales.id', 'desc')
            ->limit(200)->get();

        if (count($purchaseBySales) > 0) {

            return view('common_ajax_view.purchase_by_weight_challan_search_result', compact('purchaseBySales'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    public function PurchaseByWeightChallanList()
    {
        $ps_challans = DB::table('purchase_by_scales')
            ->where('purchase_by_scales.is_done', 0)
            ->leftJoin('accounts as suppliers', 'purchase_by_scales.supplier_account_id', 'suppliers.id')
            ->select('purchase_by_scales.*', 'suppliers.name as supplier_name', 'suppliers.phone as supplier_phone')
            ->orderBy('purchase_by_scales.date_ts', 'desc')
            ->orderBy('purchase_by_scales.status', 'asc')
            ->limit(200)->get();

        return view('common_ajax_view.purchase_by_weight_challan_table_rows', compact('ps_challans'));
    }

    public function getDoList()
    {
        $weights = DB::table('weight_scales')
            ->leftJoin('sales as do', 'weight_scales.delivery_order_id', 'do.id')
            ->leftJoin('accounts as customers', 'do.customer_account_id', 'customers.id')
            ->where('weight_scales.is_vehicle_done', 0)
            ->select('weight_scales.id', 'weight_scales.sale_id', 'weight_scales.do_car_number', 'weight_scales.do_driver_name', 'weight_scales.do_driver_phone', 'weight_scales.first_weight', 'weight_scales.second_weight', 'weight_scales.do_car_last_weight', 'do.id as do_id', 'do.invoice_id', 'do.do_id as do_str_id', 'do.date', 'do.customer_account_id', 'do.order_discount_type', 'do.expire_date', 'do.order_discount', 'do.total_item', 'do.total_delivered_qty', 'do.order_tax_percent', 'do.net_total_amount', 'do.total_payable_amount', 'do.paid', 'do.due', 'do.shipment_charge', 'do.sale_note', 'do.shipping_address', 'do.receiver_phone', 'do.sale_account_id', 'customers.name as cmr_name')
            ->orderByRaw("SUBSTRING(weight_scales.do_car_number, POSITION(' ' IN weight_scales.do_car_number), CHAR_LENGTH(weight_scales.do_car_number)) ASC")
            ->limit(200)->get();

        return view('common_ajax_view.do_vehicle_rows', compact('weights'));
    }

    public function searchRequisitions($key_word)
    {
        $requisitions = DB::table('purchase_requisitions')
            ->where('purchase_requisitions.requisition_no', 'like', "%{$key_word}%")
            ->select('id', 'requisition_no', 'is_approved')
            ->limit(35)->get();

        if (count($requisitions) > 0) {

            return view('common_ajax_view.requisition_search_result_list', compact('requisitions'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    public function searchPo($key_word)
    {
        $purchaseOrders = DB::table('purchases')
            ->where('purchases.invoice_id', 'like', "%{$key_word}%")
            ->where('purchases.purchase_status', 3)
            ->leftJoin('purchase_requisitions', 'purchases.requisition_id', 'purchase_requisitions.id')
            ->select('purchases.id as purchase_order_id', 'purchases.invoice_id as po_id', 'purchases.supplier_account_id', 'purchases.warehouse_id', 'purchase_requisitions.requisition_no', 'purchase_requisitions.id as requisition_id')
            ->limit(35)->get();

        if (count($purchaseOrders) > 0) {

            return view('common_ajax_view.po_search_result_list', compact('purchaseOrders'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    public function searchPurchase($key_word)
    {
        $purchases = DB::table('purchases')
            ->where('purchases.invoice_id', 'like', "%{$key_word}%")
            ->where('purchases.purchase_status', 1)
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->select('purchases.id as purchase_id', 'purchases.invoice_id as p_invoice_id', 'purchases.supplier_account_id', 'purchases.warehouse_id', 'warehouses.warehouse_name')->limit(35)->get();

        if (count($purchases) > 0) {

            return view('common_ajax_view.purchase_search_result_list', compact('purchases'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    public function purchaseProducts($purchaseId)
    {
        // return DB::table('purchase_products')
        //     ->where('purchase_products.purchase_id', $purchaseId)
        //     ->leftJoin('products', 'purchase_products.product_id', 'products.id')
        //     ->leftJoin('product_variants', 'purchase_products.product_variant_id', 'product_variants.id')
        //     ->select('purchase_products.*', 'products.id as product_id', 'products.name as product_name', 'products.product_cost_with_tax', 'products.is_manage_stock', 'products.tax_type', 'products.product_code', 'product_variants.variant_name', 'product_variants.variant_code', 'product_variants.variant_cost_with_tax')->get();

        $purchaseProducts = PurchaseProduct::with([
            'purchase:id,warehouse_id',
            'purchase.warehouse:id,warehouse_name,warehouse_code',
            'product:id,name,product_code,unit_id',
            'product.unit:id,name,code_name',
            'product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'variant:id,variant_name,variant_code',
            'purchaseUnit:id,name,base_unit_id,base_unit_multiplier',
            'purchaseUnit.baseUnit:id,name,base_unit_id',
        ])->where('purchase_id', $purchaseId)->get();

        $itemUnitsArray = [];
        foreach ($purchaseProducts as $purchaseProduct) {

            if (isset($purchaseProduct->product_id)) {

                $itemUnitsArray[$purchaseProduct->product_id][] = [
                    'unit_id' => $purchaseProduct->product->unit->id,
                    'unit_name' => $purchaseProduct->product->unit->name,
                    'unit_code_name' => $purchaseProduct->product->unit->code_name,
                    'base_unit_multiplier' => 1,
                    'multiplier_details' => '',
                    'is_base_unit' => 1,
                ];
            }

            if (count($purchaseProduct?->product?->unit?->childUnits) > 0) {

                foreach ($purchaseProduct?->product?->unit?->childUnits as $unit) {

                    $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $purchaseProduct?->product?->unit?->name . ')';

                    array_push($itemUnitsArray[$purchaseProduct->product_id], [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'unit_code_name' => $unit->code_name,
                        'base_unit_multiplier' => $unit->base_unit_multiplier,
                        'multiplier_details' => $multiplierDetails,
                        'is_base_unit' => 0,
                    ]);
                }
            }
        }

        $view = view('common_ajax_view.purchased_products_for_purchase_return', ['purchaseProducts' => $purchaseProducts])->render();

        return [
            'view' => $view,
            'units' => json_encode($itemUnitsArray),
        ];
    }

    public function poProducts($orderId)
    {
        $orderProducts = PurchaseOrderProduct::with([
            'product:id,name,product_code,unit_id',
            'product.unit:id,name,code_name',
            'product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'variant:id,variant_name,variant_code',
            'orderUnit:id,name,base_unit_id,base_unit_multiplier',
            'orderUnit.baseUnit:id,name,base_unit_id',
        ])->where('purchase_order_products.purchase_id', $orderId)->where('pending_quantity', '>', 0)->get();

        if (count($orderProducts) == 0) {

            return response()->json(['errorMsg' => 'All Item has been Received on this PO.']);
        }

        $itemUnitsArray = [];
        foreach ($orderProducts as $orderProduct) {

            if (isset($orderProduct->product_id)) {

                $itemUnitsArray[$orderProduct->product_id][] = [
                    'unit_id' => $orderProduct->product->unit->id,
                    'unit_name' => $orderProduct->product->unit->name,
                    'unit_code_name' => $orderProduct->product->unit->code_name,
                    'base_unit_multiplier' => 1,
                    'multiplier_details' => '',
                    'is_base_unit' => 1,
                ];
            }

            if (count($orderProduct?->product?->unit?->childUnits) > 0) {

                foreach ($orderProduct?->product?->unit?->childUnits as $unit) {

                    $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $orderProduct?->product?->unit?->name . ')';

                    array_push($itemUnitsArray[$orderProduct->product_id], [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'unit_code_name' => $unit->code_name,
                        'base_unit_multiplier' => $unit->base_unit_multiplier,
                        'multiplier_details' => $multiplierDetails,
                        'is_base_unit' => 0,
                    ]);
                }
            }
        }

        $view = view('common_ajax_view.po_product_list', ['orderProducts' => $orderProducts])->render();

        return [
            'view' => $view,
            'units' => json_encode($itemUnitsArray),
        ];
    }

    public function searchReceiveStocks($key_word)
    {
        $receiveStocks = DB::table('receive_stocks')
            ->where('receive_stocks.voucher_no', 'like', "%{$key_word}%")
            ->orWhere('receive_stocks.challan_no', 'like', "%{$key_word}%")
            ->leftJoin('purchases', 'receive_stocks.id', 'purchases.receive_stock_id')
            ->select(
                'receive_stocks.id',
                'receive_stocks.voucher_no',
                'receive_stocks.supplier_account_id',
                'receive_stocks.challan_no',
                'receive_stocks.challan_date',
                'receive_stocks.net_weight',
                'receive_stocks.vehicle_no',
                'purchases.invoice_id as p_invoice_id'
            )
            ->limit(35)
            ->get();

        if (count($receiveStocks) > 0) {

            return view('common_ajax_view.receive_stocks_search_result_list', compact('receiveStocks'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    public function getReceiveStockProducts($receiveStockId)
    {
        $receiveStockProducts = ReceiveStockProduct::with([
            'product:id,name,product_code,product_cost,product_cost_with_tax,profit,product_price,unit_id,tax_ac_id,tax_type',
            'product.unit:id,name',
            'product.unit.childUnits:id,name,base_unit_id,base_unit_multiplier',
            'product.tax:id,tax_percent',
            'variant:id,variant_name,variant_code,variant_cost,variant_cost_with_tax,variant_profit,variant_price',
            'receiveUnit:id,name,code_name,base_unit_id,base_unit_multiplier',
            'receiveUnit.baseUnit:id,name,base_unit_id',
            'poProduct:id,unit_cost,unit_discount,unit_discount_amount,unit_discount_type,unit_cost_with_discount,subtotal,tax_ac_id,unit_tax_percent,unit_tax_amount,tax_type,net_unit_cost,line_total',
        ])->where('receive_stock_id', $receiveStockId)->get();

        $itemUnitsArray = [];
        foreach ($receiveStockProducts as $rsProduct) {

            if (isset($rsProduct->product_id)) {

                $itemUnitsArray[$rsProduct->product_id][] = [
                    'unit_id' => $rsProduct->product->unit->id,
                    'unit_name' => $rsProduct->product->unit->name,
                    'unit_code_name' => $rsProduct->product->unit->code_name,
                    'base_unit_multiplier' => 1,
                    'multiplier_details' => '',
                    'is_base_unit' => 1,
                ];
            }

            if (count($rsProduct?->product?->unit?->childUnits) > 0) {

                foreach ($rsProduct?->product?->unit?->childUnits as $unit) {

                    $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $rsProduct?->product?->unit?->name . ')';

                    array_push($itemUnitsArray[$rsProduct->product_id], [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'unit_code_name' => $unit->code_name,
                        'base_unit_multiplier' => $unit->base_unit_multiplier,
                        'multiplier_details' => $multiplierDetails,
                        'is_base_unit' => 0,
                    ]);
                }
            }
        }

        $view = view('common_ajax_view.receive_stock_product_list', [
            'receiveStockProducts' => $receiveStockProducts,
        ])->render();

        return [
            'view' => $view,
            'units' => json_encode($itemUnitsArray),
        ];
    }

    public function getDoProducts($saleId, $weightId, CodeGenerationServiceInterface $generator)
    {
        $weight = '';
        if ($weightId != 'null') {

            $weight = DB::table('weight_scales')->where('id', $weightId)->select('reserve_invoice_id')->first();
        }

        $do = DB::table('sales')->where('sales.id', $saleId)->select('id')->first();

        // $invoiceId = $weight ? $weight->reserve_invoice_id : $generator->generateMonthWise(table: 'sales', column: 'invoice_id', prefix: auth()->user()->user_id, splitter: '-', suffixSeparator: '-');
        $invoiceId = $weight ? $weight->reserve_invoice_id : '';

        $do_products = DB::table('sale_products')
            ->where('sale_products.sale_id', $do->id)
            ->leftJoin('warehouses', 'sale_products.stock_warehouse_id', 'warehouses.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('units as saleUnit', 'sale_products.unit_id', 'saleUnit.id')
            ->leftJoin('units as baseUnit', 'saleUnit.base_unit_id', 'baseUnit.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->select(
                'sale_products.id',
                'sale_products.sale_id',
                'sale_products.product_id',
                'sale_products.product_variant_id as variant_id',
                'sale_products.unit_price_exc_tax',
                'sale_products.price_type',
                'sale_products.pr_amount',
                'sale_products.unit_price_inc_tax',
                'sale_products.unit_discount_type',
                'sale_products.unit_discount',
                'sale_products.unit_discount_amount',
                'sale_products.unit',
                'sale_products.tax_ac_id',
                'sale_products.unit_tax_percent',
                'sale_products.unit_tax_amount',
                'sale_products.ordered_quantity',
                'sale_products.do_left_qty',
                'sale_products.quantity',
                'sale_products.do_delivered_qty',
                'sale_products.stock_branch_id',
                'sale_products.stock_warehouse_id',
                'sale_products.subtotal',
                'saleUnit.id as sale_unit_id',
                'saleUnit.name as sale_unit_name',
                'baseUnit.id as base_unit_id',
                'baseUnit.name as base_unit_name',
                'warehouses.warehouse_name as w_name',
                'warehouses.warehouse_code as w_code',
                'products.name as product_name',
                'products.is_manage_stock',
                'products.product_code',
                'products.product_cost_with_tax',
                'products.tax_type',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
            )->orderBy('sale_products.id', 'desc')->get();

        return response()->json(['invoice_id' => $invoiceId, 'do_products' => $do_products]);
    }

    public function purchaseByWeightProductsForPurchases($purchaseScaleId)
    {
        $purchaseByScaleProducts = PurchaseByScaleWeight::with(
            'product:id,name,product_code,product_cost,product_cost_with_tax,profit,product_price,unit_id,tax_ac_id,tax_type',
            'product.unit:id,name,code_name',
            'product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'product.tax:id,tax_percent',
            'variant:id,variant_name,variant_code,variant_cost,variant_cost_with_tax,variant_profit,variant_price',
        )->where('purchase_by_scale_id', $purchaseScaleId)->where('product_id', '!=', null)->get();

        $itemUnitsArray = [];
        foreach ($purchaseByScaleProducts as $purchaseByScaleProduct) {

            if (isset($purchaseByScaleProduct->product_id)) {

                $itemUnitsArray[$purchaseByScaleProduct->product_id][] = [
                    'unit_id' => $purchaseByScaleProduct->product->unit->id,
                    'unit_name' => $purchaseByScaleProduct->product->unit->name,
                    'unit_code_name' => $purchaseByScaleProduct->product->unit->code_name,
                    'base_unit_multiplier' => 1,
                    'multiplier_details' => '',
                    'is_base_unit' => 1,
                ];
            }
        }

        $view = view('common_ajax_view.purchase_by_weight_products_for_purchases', ['purchaseByScaleProducts' => $purchaseByScaleProducts])->render();

        return [
            'view' => $view,
            'units' => json_encode($itemUnitsArray),
        ];
    }

    public function getRequisitionProductsForPurchase($requisitionId)
    {
        $requisitionProducts = PurchaseRequisitionProduct::with([
            'product:id,name,product_code,product_cost,product_cost_with_tax,profit,product_price,unit_id,tax_ac_id,tax_type',
            'product.unit:id,name,code_name',
            'product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'product.tax:id,tax_percent',
            'variant:id,variant_name,variant_code,variant_cost,variant_cost_with_tax,variant_profit,variant_price',
            'requisitionUnit:id,name,base_unit_id,base_unit_multiplier',
            'requisitionUnit.baseUnit:id,name,base_unit_id',
        ])->where('requisition_id', $requisitionId)->where('left_qty', '>', 0)->get();

        $itemUnitsArray = [];

        foreach ($requisitionProducts as $rqProduct) {

            if (isset($rqProduct->product_id)) {

                $itemUnitsArray[$rqProduct->product_id][] = [
                    'unit_id' => $rqProduct->product->unit->id,
                    'unit_name' => $rqProduct->product->unit->name,
                    'unit_code_name' => $rqProduct->product->unit->code_name,
                    'base_unit_multiplier' => 1,
                    'multiplier_details' => '',
                    'is_base_unit' => 1,
                ];
            }

            if (count($rqProduct?->product?->unit?->childUnits) > 0) {

                foreach ($rqProduct?->product?->unit?->childUnits as $unit) {

                    $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $rqProduct?->product?->unit?->name . ')';

                    array_push($itemUnitsArray[$rqProduct->product_id], [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'unit_code_name' => $unit->code_name,
                        'base_unit_multiplier' => $unit->base_unit_multiplier,
                        'multiplier_details' => $multiplierDetails,
                        'is_base_unit' => 0,
                    ]);
                }
            }
        }

        if (count($requisitionProducts) == 0) {

            return response()->json(['errorMsg' => 'All Item has been purchased/received of the requisition.']);
        }

        $view = view('common_ajax_view.requisition_product_list_for_purchase', ['requisitionProducts' => $requisitionProducts])->render();

        return [
            'view' => $view,
            'units' => json_encode($itemUnitsArray),
        ];
    }

    public function getRequisitionProductsForReceiveStock($requisitionId)
    {
        $requisitionProducts = PurchaseRequisitionProduct::with([
            'product:id,name,product_code,product_cost,product_cost_with_tax,profit,product_price,unit_id,tax_ac_id,tax_type',
            'product.unit:id,name,code_name',
            'product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'product.tax:id,tax_percent',
            'variant:id,variant_name,variant_code,variant_cost,variant_cost_with_tax,variant_profit,variant_price',
            'requisitionUnit:id,name,base_unit_id,base_unit_multiplier',
            'requisitionUnit.baseUnit:id,name,base_unit_id',
        ])->where('requisition_id', $requisitionId)->where('left_qty', '>', 0)->get();

        $itemUnitsArray = [];
        foreach ($requisitionProducts as $rqProduct) {

            if (isset($rqProduct->product_id)) {

                $itemUnitsArray[$rqProduct->product_id][] = [
                    'unit_id' => $rqProduct->product->unit->id,
                    'unit_name' => $rqProduct->product->unit->name,
                    'unit_code_name' => $rqProduct->product->unit->code_name,
                    'base_unit_multiplier' => 1,
                    'multiplier_details' => '',
                    'is_base_unit' => 1,
                ];
            }

            if (count($rqProduct?->product?->unit?->childUnits) > 0) {

                foreach ($rqProduct?->product?->unit?->childUnits as $unit) {

                    $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $rqProduct?->product?->unit?->name . ')';

                    array_push($itemUnitsArray[$rqProduct->product_id], [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'unit_code_name' => $unit->code_name,
                        'base_unit_multiplier' => $unit->base_unit_multiplier,
                        'multiplier_details' => $multiplierDetails,
                        'is_base_unit' => 0,
                    ]);
                }
            }
        }

        if (count($requisitionProducts) == 0) {

            return response()->json(['errorMsg' => 'All Item has been purchased/received of the requisition.']);
        }

        $view = view('common_ajax_view.requisition_product_list_for_receive_stock', ['requisitionProducts' => $requisitionProducts])->render();

        return [
            'view' => $view,
            'units' => json_encode($itemUnitsArray),
        ];
    }

    // Recent Add sale
    public function recentSale($create_by)
    {
        $sales = Sale::with('customer')
            ->where('admin_id', auth()->user()->id)
            ->where('status', 1)
            ->where('created_by', $create_by)
            ->where('is_return_available', 0)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('common_ajax_view.recent_sale_list', compact('sales'));
    }

    // Get all recent quotations ** requested by ajax **
    public function recentQuotations($create_by)
    {
        $quotations = Sale::where('admin_id', auth()->user()->id)
            ->where('status', 4)
            ->where('created_by', $create_by)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('common_ajax_view.recent_quotation_list', compact('quotations'));
    }

    // Get all recent drafts ** requested by ajax **
    public function recentDrafts($create_by)
    {
        $drafts = Sale::with('customer')
            ->where('admin_id', auth()->user()->id)
            ->where('status', 2)
            ->where('created_by', $create_by)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('common_ajax_view.recent_draft_list', compact('drafts'));
    }

    // Search product
    public function searchProductForReportFilter($product_name)
    {
        $products = DB::table('products')
            ->where('name', 'like', "%{$product_name}%")
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->select(
                'products.id as product_id',
                'products.name',
                'products.product_code',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
            )->get();

        if (count($products) > 0) {

            return view('reports.product_sale_report.ajax_view.search_result', compact('products'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    public function countSalesQuotationsOrdersDo()
    {
        return $count = DB::table('sales')
            ->whereIn('status', [3, 4])
            ->select(
                DB::raw('count(case when sales.status = 4 and sales.order_status = 0 then 1 end) as total_quotation'),
                DB::raw('count(case when sales.order_status = 1 and sales.do_status = 0 then 1 end) as total_ordered'),
                DB::raw('count(case when sales.do_status = 1 and sales.delivery_qty_status != 2 then 1 end) as total_uncompleted_do'),
            )
            //->groupBy('sales.status')
            ->get();
    }

    public function addQuickRequesterModal()
    {
        return view('common_ajax_view.add_quick_requester_modal');
    }

    // Add product modal view with data
    public function addQuickProductModal()
    {
        $units = DB::table('units')->where('base_unit_id', null)->select('id', 'name', 'code_name')->get();

        $warranties = DB::table('warranties')->select('id', 'name', 'type')->get();

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $categories = DB::table('categories')->where('parent_category_id', null)->orderBy('id', 'DESC')->get();

        $brands = DB::table('brands')->get();

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        return view('common_ajax_view.add_quick_product', compact('units', 'warranties', 'taxAccounts', 'categories', 'brands', 'warehouses'));
    }

    public function addQuickProductStore(Request $request)
    {
        $addProduct = new Product();

        $tax_ac_id = null;

        if ($request->tax_ac_id) {

            $tax_ac_id = explode('-', $request->tax_ac_id)[0];
        }

        $request->validate(
            [
                'name' => 'required',
                'unit_id' => 'required',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
            ]
        );

        // generate product code
        $l = 6;
        $b = 0;
        $code = '';
        while ($b < $l) {

            $code .= rand(1, 9);
            $b++;
        }

        $generalSettings = DB::table('general_settings')->first();

        $productCodePrefix = json_decode($generalSettings->product, true)['product_code_prefix'];

        $addProduct->type = 1;
        $addProduct->name = $request->name;
        $addProduct->product_code = $request->product_code ? $request->product_code : $productCodePrefix . $code;
        $addProduct->category_id = $request->category_id;
        $addProduct->parent_category_id = $request->child_category_id;
        $addProduct->brand_id = $request->brand_id;
        $addProduct->unit_id = $request->unit_id;
        $addProduct->product_cost = $request->product_cost ? $request->product_cost : 0;
        $addProduct->profit = $request->profit ? $request->profit : 0;
        $addProduct->product_cost_with_tax = $request->product_cost_with_tax ? $request->product_cost_with_tax : 0;
        $addProduct->product_price = $request->product_price ? $request->product_price : 0;
        $addProduct->alert_quantity = $request->alert_quantity;
        $addProduct->tax_ac_id = $tax_ac_id;
        $addProduct->tax_type = $request->tax_type;
        $addProduct->product_details = $request->product_details;
        $addProduct->is_purchased = 1;
        $addProduct->barcode_type = $request->barcode_type;
        $addProduct->warranty_id = $request->warranty_id;
        $addProduct->is_purchased = 1;
        $addProduct->is_show_in_ecom = isset($request->is_show_in_ecom) ? 1 : 0;
        $addProduct->is_show_emi_on_pos = isset($request->is_show_emi_on_pos) ? 1 : 0;
        $addProduct->quantity = $request->quantity ? $request->quantity : 0;
        $addProduct->save();

        // Add opening stock
        $this->productUtil->addOpeningStock(
            product_id: $addProduct->id,
            variant_id: null,
            unit_cost_inc_tax: $request->product_cost_with_tax ? $request->product_cost_with_tax : 0,
            quantity: $request->quantity ? $request->quantity : 0,
            subtotal: $request->subtotal ? $request->subtotal : 0,
            warehouse_id: isset($request->warehouse_count) ? $request->warehouse_id : null,
        );

        if (isset($request->warehouse_count)) {

            if ($request->warehouse_id) {

                // Add product Warehouse
                $addProductWarehouse = new ProductWarehouse();
                $addProductWarehouse->warehouse_id = $request->warehouse_id;
                $addProductWarehouse->product_id = $addProduct->id;
                $addProductWarehouse->product_quantity = $request->quantity ? $request->quantity : 0;
                $addProductWarehouse->save();
            }

            // Add product Branch
            $addProductBranch = new ProductBranch();
            $addProductBranch->product_id = $addProduct->id;
            $addProductBranch->save();
        } else {

            // Add product Branch
            $addProductBranch = new ProductBranch();
            $addProductBranch->product_id = $addProduct->id;
            $addProductBranch->product_quantity = $request->quantity ? $request->quantity : 0;
            $addProductBranch->save();
        }

        $product = Product::with(['tax', 'unit:id,name,code_name', 'unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier'])->where('id', $addProduct->id)->first();

        $warehouse = '';
        if ($request->warehouse_id) {

            $warehouse = DB::table('warehouses')->where('id', $request->warehouse_id)
                ->select('warehouse_name', 'warehouse_code')->first();
        }

        return response()->json([
            'item' => $product,
            'warehouse_id' => isset($request->warehouse_count) ? $request->warehouse_id : null,
            'warehouse' => $warehouse,
        ]);
    }

    public function getLc($lc_id)
    {
        return DB::table('lcs')->where('lcs.id', $lc_id)
            ->leftJoin('currencies', 'lcs.currency_id', 'currencies.id')
            ->select('lcs.id', 'lcs.currency_id', 'currencies.code as currency_code')
            ->first();
    }

    public function categoryItems($categoryId)
    {
        $items = DB::table('products')
            ->where('products.category_id', $categoryId)
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as subcategories', 'products.parent_category_id', 'subcategories.id')
            ->select(
                'products.id as p_id',
                'products.category_id',
                'products.parent_category_id as subcategory_id',
                'products.name as p_name',
                'products.product_cost as p_cost',
                'products.product_price as p_price',
                'products.profit as p_profit',
                'product_variants.id as v_id',
                'product_variants.variant_name as v_name',
                'product_variants.variant_cost as v_cost',
                'product_variants.variant_profit as v_profit',
                'product_variants.variant_price as v_price',
                'categories.name as cate_name',
                'subcategories.name as sub_cate_name',
            )->orderBy('products.category_id', 'asc')->orderBy('products.parent_category_id', 'asc')->get();

        return view('common_ajax_view.category_items', compact('items'));
    }

    public function subcategoryItems($subcategoryId)
    {
        $items = DB::table('products')
            ->where('products.parent_category_id', $subcategoryId)
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->select(
                'products.id as p_id',
                'products.name as p_name',
                'products.product_cost as p_cost',
                'products.product_price as p_price',
                'products.profit as p_profit',
                'product_variants.id as v_id',
                'product_variants.variant_name as v_name',
                'product_variants.variant_cost as v_cost',
                'product_variants.variant_profit as v_profit',
                'product_variants.variant_price as v_price',
            )->get();

        return view('common_ajax_view.category_items', compact('items'));
    }

    public function accountsByGroupId($groupId)
    {
        return DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('accounts.account_group_id', $groupId)
            ->select(
                'accounts.id',
                'accounts.name',
                'account_groups.name as group_name',
            )->get();
    }
}
