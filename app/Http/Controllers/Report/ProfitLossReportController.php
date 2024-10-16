<?php

namespace App\Http\Controllers\Report;

// use App\Models\Purchase;
use App\Http\Controllers\Controller;
// use App\Models\ProductOpeningStock;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitLossReportController extends Controller
{
    public function __construct()
    {
    }

    // Index view of profit loss report
    public function index()
    {
        if (! auth()->user()->can('daily_profit_loss')) {

            abort(403, 'Access Forbidden.');
        }

        return view('reports.profit_loss_report.index');
    }

    // Sale purchase and profit
    public function salePurchaseProfit()
    {
        //return  $request->date_range;
        $stock_adjustments = '';
        $sales = '';
        $saleReturns = '';
        $saleProducts = '';
        $expanses = '';
        $payrolls = '';
        $transferStBranch = '';
        $transferStWarehouse = '';

        $transferStBranchQuery = DB::table('transfer_stock_to_branches')
            ->select(DB::raw('sum(shipping_charge) as b_total_shipment_charge'));

        $transferStWarehouseQuery = DB::table('transfer_stock_to_warehouses')
            ->select(DB::raw('sum(shipping_charge) as w_total_shipment_charge'));

        // $saleProductQuery = DB::table('sale_products')->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
        //     ->select(DB::raw('sum(quantity * unit_cost_inc_tax) as total_unit_cost'));

        // $saleProductQuery = DB::table('purchase_sale_product_chains')
        //     ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
        //     ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
        //     ->leftJoin('products', 'purchase_sale_product_chains.sale_product_id', 'products.id')
        //     ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
        //     ->select(
        //         DB::raw(
        //             'SUM(IF(purchase_products.net_unit_cost, products.product_cost_with_tax, 0)
        //                 * purchase_sale_product_chains.sold_qty
        //             ) as total_unit_cost'
        //         )
        //     );

        $saleProductQuery = DB::table('purchase_sale_product_chains')
            ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
            ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('products', 'purchase_sale_product_chains.sale_product_id', 'products.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->where('sales.status', 1)
            ->select(
                DB::raw('SUM(purchase_products.net_unit_cost * purchase_sale_product_chains.sold_qty) as total_unit_cost')
                // DB::raw(
                //     'SUM(IF(purchase_products.net_unit_cost, products.product_cost_with_tax, 0)
                //         * purchase_sale_product_chains.sold_qty
                //     ) as total_unit_cost'
                // )
            );

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
            DB::raw('sum(recovered_amount) as total_recovered')
        );

        $saleQuery = DB::table('sales')->where('sales.status', 1)->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(order_tax_amount) as total_order_tax'),
        );

        $saleReturnQuery = DB::table('sale_returns')->select(
            DB::raw('sum(total_return_amount) as total_sale_return'),
        );

        // $payrollQuery = DB::table('hrm_payroll_payments')
        //     ->leftJoin('hrm_payrolls', 'hrm_payroll_payments.payroll_id', 'hrm_payrolls.id')
        //     ->leftJoin('users', 'hrm_payrolls.user_id', 'users.id')
        //     ->select(DB::raw('sum(hrm_payroll_payments.paid) as total_payroll'));

        $expenseQuery = DB::table('expanses')->select(DB::raw('sum(net_total_amount) as total_expense'));
        $stock_adjustments = $adjustmentQuery->get();
        $sales = $saleQuery->whereIn('sales.status', [1, 3])->get();
        $saleReturns = $saleReturnQuery->get();
        $expense = $expenseQuery->get();

        // $payrolls = $payrollQuery->get();

        //$saleProducts = $saleProductQuery->where('sales.status', 1)->get();
        $saleProducts = $saleProductQuery->get();
        $transferStBranch = $transferStBranchQuery->get();
        $transferStWarehouse = $transferStWarehouseQuery->get();

        $totalStockAdjustmentAmount = $stock_adjustments->sum('total_adjustment');
        $totalStockAdjustmentRecovered = $stock_adjustments->sum('total_recovered');
        $totalSale = $sales->sum('total_sale');
        $totalSaleReturn = $saleReturns->sum('total_sale_return');
        $totalOrderTax = $sales->sum('total_order_tax');
        $totalExpense = $expense->sum('total_expense');
        // $totalPayroll = $payrolls->sum('total_payroll');
        $totalTotalUnitCost = $saleProducts->sum('total_unit_cost');
        $totalTransferShipmentCost = $transferStBranch->sum('b_total_shipment_charge') + $transferStWarehouse->sum('w_total_shipment_charge');

        return view(
            'reports.profit_loss_report.ajax_view.sale_purchase_and_profit_view',
            compact(
                'totalStockAdjustmentAmount',
                'totalStockAdjustmentRecovered',
                'totalSale',
                'totalExpense',
                'totalSaleReturn',
                'totalOrderTax',
                // 'totalPayroll',
                'totalTotalUnitCost',
                'totalTransferShipmentCost',
            )
        );
    }

    // Filter sale purchase and profit
    public function filterSalePurchaseProfit(Request $request)
    {
        $stock_adjustments = '';
        $sales = '';
        $saleReturns = '';
        $saleProducts = '';
        $expanses = '';
        $payrolls = '';
        $saleProducts = '';
        $transferStBranch = '';
        $transferStWarehouse = '';

        $transferStBranchQuery = DB::table('transfer_stock_to_branches')
            ->select(DB::raw('sum(shipping_charge) as b_total_shipment_charge'));

        $transferStWarehouseQuery = DB::table('transfer_stock_to_warehouses')
            ->select(DB::raw('sum(shipping_charge) as w_total_shipment_charge'));

        // $saleProductQuery = DB::table('sale_products')->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
        //     ->select(DB::raw('sum(quantity * unit_cost_inc_tax) as total_unit_cost'));

        $saleProductQuery = DB::table('purchase_sale_product_chains')
            ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
            ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('products', 'purchase_sale_product_chains.sale_product_id', 'products.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->select(
                DB::raw('SUM(purchase_products.net_unit_cost * purchase_sale_product_chains.sold_qty) as total_unit_cost')
                // DB::raw(
                //     'SUM(IF(purchase_products.net_unit_cost, products.product_cost_with_tax, 0)
                //         * purchase_sale_product_chains.sold_qty
                //     ) as total_unit_cost'
                // )
            );

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
            DB::raw('sum(recovered_amount) as total_recovered')
        );

        $saleQuery = DB::table('sales')->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(order_tax_amount) as total_order_tax'),
        );

        $saleReturnQuery = DB::table('sale_returns')->select(
            DB::raw('sum(total_return_amount) as total_sale_return'),
        );

        $expenseQuery = DB::table('expanses')->select(DB::raw('sum(net_total_amount) as total_expense'));

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $adjustmentQuery->whereBetween('stock_adjustments.date_ts', $date_range);
            $saleQuery->whereBetween('sales.report_date', $date_range);
            $saleReturnQuery->whereBetween('sale_returns.report_date', $date_range)->get();
            $expenseQuery->whereBetween('expanses.report_date', $date_range);
            // $payrollQuery->whereBetween('hrm_payroll_payments.report_date', $date_range);
            $saleProductQuery->whereBetween('sales.report_date', $date_range);
            $transferStBranchQuery->whereBetween('transfer_stock_to_branches.report_date', $date_range);
            $transferStWarehouseQuery->whereBetween('transfer_stock_to_warehouses.report_date', $date_range);
        }

        $stock_adjustments = $adjustmentQuery->get();
        $sales = $saleQuery->whereIn('sales.status', [1, 3])->get();
        $saleReturns = $saleReturnQuery->get();
        $expense = $expenseQuery->get();
        // $payrolls = $payrollQuery->get();
        $saleProducts = $saleProductQuery->whereIn('sales.status', [1, 3])->get();
        $transferStBranch = $transferStBranchQuery->get();
        $transferStWarehouse = $transferStWarehouseQuery->get();

        $totalStockAdjustmentAmount = $stock_adjustments->sum('total_adjustment');
        $totalStockAdjustmentRecovered = $stock_adjustments->sum('total_recovered');
        $totalSale = $sales->sum('total_sale');
        $totalSaleReturn = $saleReturns->sum('total_return');
        $totalOrderTax = $sales->sum('total_order_tax');
        $totalExpense = $expense->sum('total_expense');
        // $totalPayroll = $payrolls->sum('total_payroll');
        $totalTotalUnitCost = $saleProducts->sum('total_unit_cost');
        $totalTransferShipmentCost = $transferStBranch->sum('b_total_shipment_charge') + $transferStWarehouse->sum('w_total_shipment_charge');

        return view(
            'reports.profit_loss_report.ajax_view.filtered_sale_purchase_and_profit_view',
            compact(
                'totalStockAdjustmentAmount',
                'totalStockAdjustmentRecovered',
                'totalSale',
                'totalExpense',
                'totalSaleReturn',
                'totalOrderTax',
                // 'totalPayroll',
                'totalTotalUnitCost',
                'totalTransferShipmentCost',
            )
        );
    }

    // Print Profit Loss method
    public function printProfitLoss(Request $request)
    {
        $stock_adjustments = '';
        $sales = '';
        $saleProducts = '';
        $expanses = '';
        // $payrolls = '';
        $saleProducts = '';
        $transferStBranch = '';
        $transferStWarehouse = '';
        $fromDate = '';
        $toDate = '';

        $transferStBranchQuery = DB::table('transfer_stock_to_branches')
            ->select(DB::raw('sum(shipping_charge) as b_total_shipment_charge'));

        $transferStWarehouseQuery = DB::table('transfer_stock_to_warehouses')
            ->select(DB::raw('sum(shipping_charge) as w_total_shipment_charge'));

        $saleProductQuery = DB::table('purchase_sale_product_chains')
            ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
            ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->select(
                DB::raw('SUM(net_unit_cost * sold_qty) as total_unit_cost'),
                // DB::raw(
                //     'SUM(IF(purchase_products.net_unit_cost, products.product_cost_with_tax, 0)
                //         * purchase_sale_product_chains.sold_qty
                //     ) as total_unit_cost'
                // )
            );

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
            DB::raw('sum(recovered_amount) as total_recovered')
        );

        $saleQuery = DB::table('sales')->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(sale_return_amount) as total_return'),
            DB::raw('sum(order_tax_amount) as total_order_tax'),
        );

        $expenseQuery = DB::table('expanses')->select(DB::raw('sum(net_total_amount) as total_expense'));

        // $payrollQuery = DB::table('hrm_payroll_payments')
        //     ->leftJoin('hrm_payrolls', 'hrm_payroll_payments.payroll_id', 'hrm_payrolls.id')
        //     ->leftJoin('users', 'hrm_payrolls.user_id', 'users.id')
        //     ->select(DB::raw('sum(hrm_payroll_payments.paid) as total_payroll'));

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $adjustmentQuery->whereBetween('stock_adjustments.report_date_ts', $date_range);
            $saleQuery->whereBetween('sales.report_date', $date_range);
            $expenseQuery->whereBetween('expanses.report_date', $date_range);
            // $payrollQuery->whereBetween('hrm_payroll_payments.report_date', $date_range);
            $saleProductQuery->whereBetween('sales.report_date', $date_range);
            $transferStBranchQuery->whereBetween('transfer_stock_to_branches.report_date', $date_range);
            $transferStWarehouseQuery->whereBetween('transfer_stock_to_warehouses.report_date', $date_range);
        }

        $stock_adjustments = $adjustmentQuery->get();
        $sales = $saleQuery->where('sales.status', 1)->get();
        $expense = $expenseQuery->get();
        // $payrolls = $payrollQuery->get();
        $saleProducts = $saleProductQuery->where('sales.status', 1)->get();
        $transferStBranch = $transferStBranchQuery->get();
        $transferStWarehouse = $transferStWarehouseQuery->get();

        $totalStockAdjustmentAmount = $stock_adjustments->sum('total_adjustment');
        $totalStockAdjustmentRecovered = $stock_adjustments->sum('total_recovered');
        $totalSale = $sales->sum('total_sale');
        $totalReturn = $sales->sum('total_return');
        $totalOrderTax = $sales->sum('total_order_tax');
        $totalExpense = $expense->sum('total_expense');
        // $totalPayroll = $payrolls->sum('total_payroll');
        $totalTotalUnitCost = $saleProducts->sum('total_unit_cost');
        $totalTransferShipmentCost = $transferStBranch->sum('b_total_shipment_charge') + $transferStWarehouse->sum('w_total_shipment_charge');

        return view(
            'reports.profit_loss_report.ajax_view.printProfitLoss',
            compact(
                'totalStockAdjustmentAmount',
                'totalStockAdjustmentRecovered',
                'totalSale',
                'totalExpense',
                'totalReturn',
                'totalOrderTax',
                // 'totalPayroll',
                'totalTotalUnitCost',
                'totalTransferShipmentCost',
                'fromDate',
                'toDate',
            )
        );
    }
}
