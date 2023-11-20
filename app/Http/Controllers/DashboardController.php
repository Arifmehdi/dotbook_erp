<?php

namespace App\Http\Controllers;

use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

define('TODAY_DATE', Carbon::today());

class DashboardController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {
        // define('TODAY_DATE', date('Y-m-d'));
        $this->converter = $converter;
    }

    // Admin dashboard
    public function index()
    {
        $thisWeek = Carbon::now()->startOfWeek()->format('Y-m-d').'~'.Carbon::now()->endOfWeek()->format('Y-m-d');
        $thisYear = Carbon::now()->startOfYear()->format('Y-m-d').'~'.Carbon::now()->endOfYear()->format('Y-m-d');
        $thisMonth = Carbon::now()->startOfMonth()->format('Y-m-d').'~'.Carbon::now()->endOfMonth()->format('Y-m-d');
        $toDay = Carbon::now()->format('Y-m-d').'~'.Carbon::now()->endOfDay()->format('Y-m-d');

        return view('dashboard.dashboard_1', compact('thisWeek', 'thisYear', 'thisMonth', 'toDay'));
    }

    // Get dashboard card data
    public function cardData(Request $request)
    {
        $totalSales = 0;
        $totalSaleDue = 0;
        $totalSaleDiscount = 0;
        $totalPurchase = 0;
        $totalPurchaseDue = 0;
        $totalExpense = 0;

        $purchases = '';
        $sales = '';
        $expenses = '';
        $products = '';
        $users = '';
        $adjustments = '';

        $userQuery = DB::table('users');
        $purchaseQuery = DB::table('purchases')->select(
            DB::raw('sum(total_purchase_amount) as total_purchase'),
            //DB::raw('sum(case when due > 0 then due end) as total_due'),
            DB::raw('sum(due) as total_due'),
        );

        $saleQuery = DB::table('sales')->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            //DB::raw('sum(case when due > 0 then due end) as total_due'),
            DB::raw('sum(due) as total_due'),
            DB::raw('sum(order_discount) as total_discount')
        );

        $expenseQuery = DB::table('expanses')->select(
            DB::raw('sum(net_total_amount) as total_expense'),
        );

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
        );

        if ($request->date_range != 'all_time') {
            if ($request->date_range) {

                $date_range = explode('~', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1]));

                $range = [Carbon::parse($form_date), Carbon::parse($to_date)->endOfDay()];

                $saleQuery->whereBetween('sales.report_date', $range); // Final
                $purchaseQuery->whereBetween('purchases.report_date', $range);
                $expenseQuery->whereBetween('expanses.report_date', $range);
                $adjustmentQuery->whereBetween('stock_adjustments.date_ts', $range);
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $sales = $saleQuery->where('sales.status', 1)->get();
            $purchases = $purchaseQuery->get();
            $expenses = $expenseQuery->get();
            $users = $userQuery->count();
            $adjustments = $adjustmentQuery->get();
        } else {

            $sales = $saleQuery->where('sales.status', 1)->get();
            $purchases = $purchaseQuery->get();
            $expenses = $expenseQuery->get();
            $users = $userQuery->count();
            $adjustments = $adjustmentQuery->get();
        }

        $totalSales = $sales->sum('total_sale');
        $totalSaleDue = $sales->sum('total_due');
        $totalSaleDiscount = $sales->sum('total_discount');

        $totalPurchase = $purchases->sum('total_purchase');
        $totalPurchaseDue = $purchases->sum('total_due');

        $totalExpense = $expenses->sum('total_expense');
        $products = DB::table('products')->count();
        $total_adjustment = $adjustments->sum('total_adjustment');

        return response()->json([
            'total_sale' => $this->converter->format_in_bdt($totalSales),
            'totalSaleDue' => $this->converter->format_in_bdt($totalSaleDue),
            'totalSaleDiscount' => $this->converter->format_in_bdt($totalSaleDiscount),
            'totalPurchase' => $this->converter->format_in_bdt($totalPurchase),
            'totalPurchaseDue' => $this->converter->format_in_bdt($totalPurchaseDue),
            'totalExpense' => $this->converter->format_in_bdt($totalExpense),
            'users' => $this->converter->format_in_bdt($users),
            'products' => $this->converter->format_in_bdt($products),
            'total_adjustment' => $this->converter->format_in_bdt($total_adjustment),
        ]);
    }

    public function stockAlert(Request $request)
    {
        if ($request->ajax()) {

            $alertQtyProducts = '';
            $alertQtyProducts = DB::table('products')
                ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
                ->join('units', 'products.unit_id', 'units.id')
                ->whereColumn('products.quantity', '<=', 'products.alert_quantity')
                ->where('products.is_manage_stock', 1)
                ->select(
                    [
                        'products.name',
                        'products.product_code',
                        'products.alert_quantity',
                        'products.quantity as product_quantity',
                        'product_variants.variant_quantity',
                        'product_variants.variant_name',
                        'units.name as unit_name',

                    ]
                )->orderBy('products.id', 'desc')->get();

            return DataTables::of($alertQtyProducts)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {

                    return $row->name.($row->variant_name != null ? '/'.$row->variant_name : '');
                })
                ->editColumn('stock', function ($row) {

                    return $quantity = '<span class="text-danger"><b>'.$row->product_quantity.'/'.$row->unit_name.'</b></span>';
                })
                ->rawColumns(['stock'])->make(true);
        }
    }

    public function saleOrder(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $sales = '';
            $query = DB::table('sales')->where('sales.order_status', 1)
                ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
                ->leftJoin('users', 'sales.order_by_id', 'users.id');

            if ($request->date_range != 'all_time') {

                if ($request->date_range) {

                    $date_range = explode('~', $request->date_range);
                    $form_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[1]));

                    $range = [Carbon::parse($form_date), Carbon::parse($to_date)->endOfDay()];

                    $query->whereBetween('sales.order_date', [$range]); // Final
                }
            }

            $sales = $query->select(
                'sales.*',
                'customers.name as customer_name',
                'users.prefix as c_prefix',
                'users.name as c_name',
                'users.last_name as c_last_name',
            )->orderBy('id', 'desc')->where('sales.status', 3)->get();

            return DataTables::of($sales)
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->order_date));
                })

                ->editColumn('customer', function ($row) {

                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('created_by', function ($row) {

                    return $row->c_prefix.' '.$row->c_name.' '.$row->c_last_name;
                })
                ->rawColumns(['date', 'from', 'customer', 'created_by'])
                ->make(true);
        }
    }

    public function saleDue(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $sales = '';
            $query = DB::table('sales')
                ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id');

            if ($request->date_range != 'all_time') {

                if ($request->date_range) {

                    $date_range = explode('~', $request->date_range);
                    $form_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[1]));

                    $range = [Carbon::parse($form_date), Carbon::parse($to_date)->endOfDay()];
                    $query->whereBetween('sales.report_date', $range); // Final
                }
            }

            $query->select(
                'sales.*',
                'customers.name as customer_name',
            );

            $sales = $query->where('sales.due', '>', 0)->where('sales.status', 1)->orderBy('id', 'desc')->get();

            return DataTables::of($sales)
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })

                ->editColumn('customer', function ($row) {

                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('due', function ($row) use ($generalSettings) {

                    return json_decode($generalSettings->business, true)['currency'].' '.$row->due;
                })
                ->rawColumns(['date', 'from', 'customer', 'due'])
                ->make(true);
        }
    }

    public function purchaseDue(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $purchases = '';
            $query = DB::table('purchases')
                ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id');

            if ($request->date_range != 'all_time') {

                if ($request->date_range) {

                    $date_range = explode('~', $request->date_range);
                    $form_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[1]));
                    $range = [Carbon::parse($form_date), Carbon::parse($to_date)->endOfDay()];
                    $query->whereBetween('purchases.report_date', $range); // Final
                }
            }

            $purchases = $query->select(
                'purchases.*',
                'suppliers.name as sup_name',
            )->where('purchases.due', '!=', 0)->orderBy('id', 'desc')->get();

            return DataTables::of($purchases)
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('due', function ($row) use ($generalSettings) {

                    return json_decode($generalSettings->business, true)['currency'].' '.$row->due;
                })
                ->rawColumns(['date', 'from', 'due'])
                ->make(true);
        }
    }

    public function todaySummery(Request $request)
    {
        $totalSales = 0;
        $totalSaleDue = 0;
        $totalReceive = 0;
        $totalSaleDiscount = 0;
        $totalSalesReturn = 0;
        $totalSalesShipmentCost = 0;
        $totalPurchase = 0;
        $totalPurchaseDue = 0;
        $totalPayment = 0;
        $totalPurchaseReturn = 0;
        $totalExpense = 0;
        $total_recovered = 0;
        $totalTransferShippingCost = 0;
        $purchaseTotalShipmentCost = 0;

        $purchases = '';
        $purchasePayment = '';
        $supplierPayment = '';
        $purchaseReturn = '';
        $purchaseTotalShipmentCost = '';
        $sales = '';
        $customerPayment = '';
        $salePayment = '';
        $branchTransfer = '';
        $warehouseTransfer = '';
        $saleReturn = '';
        $expenses = '';
        $adjustments = '';

        $purchaseQuery = DB::table('purchases')->select(
            DB::raw('sum(total_purchase_amount) as total_purchase'),
            DB::raw('sum(shipment_charge) as total_shipment_charge'),
            DB::raw('sum(due) as total_due')
        );

        $purchaseReturnQuery = DB::table('purchase_returns')->select(
            DB::raw('sum(total_return_amount) as total_return')
        );

        $saleQuery = DB::table('sales')->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(order_discount) as total_discount'),
            DB::raw('sum(shipment_charge) as total_shipment_charge'),
            DB::raw('sum(order_tax_amount) as total_order_tax'),
            DB::raw('sum(due) as total_due'),
        );

        $saleReturnQuery = DB::table('sale_returns')
            ->select(DB::raw('sum(total_return_amount) as total_return'));

        $expenseQuery = DB::table('expanses')->select(DB::raw('sum(net_total_amount) as total_expense'));

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
            DB::raw('sum(recovered_amount) as total_recovered')
        );

        $branchTransferQuery = DB::table('transfer_stock_to_branches')->select(
            DB::raw('sum(shipping_charge) as total_shipping_cost_br')
        );

        $warehouseTransferQuery = DB::table('transfer_stock_to_warehouses')->select(
            DB::raw('sum(shipping_charge) as total_shipping_cost_wh')
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $sales = $saleQuery->where('sales.status', 1)->whereDate('report_date', TODAY_DATE)->get();
            $purchases = $purchaseQuery->whereDate('report_date', TODAY_DATE)->get();
            $expenses = $expenseQuery->whereDate('report_date', TODAY_DATE)->get();
            $adjustments = $adjustmentQuery->whereDate('date_ts', TODAY_DATE)->get();
            $purchaseReturn = $purchaseReturnQuery->whereDate('report_date', TODAY_DATE)->get();
            $saleReturn = $saleReturnQuery->whereDate('report_date', TODAY_DATE)->get();
            $branchTransfer = $branchTransferQuery->whereDate('report_date', TODAY_DATE)->get();
            $warehouseTransfer = $warehouseTransferQuery->whereDate('report_date', TODAY_DATE)->get();
        } else {

            $sales = $saleQuery->where('sales.status', 1)->whereDate('report_date', TODAY_DATE)->get();

            $purchases = $purchaseQuery->whereDate('report_date', TODAY_DATE)->get();

            $expenses = $expenseQuery->whereDate('report_date', TODAY_DATE)->get();

            $adjustments = $adjustmentQuery->whereDate('date_ts', TODAY_DATE)->get();

            $purchaseReturn = $purchaseReturnQuery->whereDate('report_date', TODAY_DATE)->get();

            $saleReturn = $saleReturnQuery->whereDate('report_date', TODAY_DATE)->get();

            $branchTransfer = $branchTransferQuery->whereDate('report_date', TODAY_DATE)->get();

            $warehouseTransfer = $warehouseTransferQuery->whereDate('report_date', TODAY_DATE)->get();
        }

        $totalSales = $sales->sum('total_sale');
        $totalSaleDue = $sales->sum('total_due');
        $totalSaleDiscount = $sales->sum('total_discount');
        $totalSaleTax = $sales->sum('total_order_tax');
        $totalSalesReturn = $saleReturn->sum('total_return');
        $totalSalesShipmentCost = $sales->sum('total_shipment_charge');
        $totalPurchase = $purchases->sum('total_purchase');
        $totalPurchaseDue = $purchases->sum('total_due');
        $totalPurchaseReturn = $purchaseReturn->sum('total_return');
        $totalExpense = $expenses->sum('total_expense');
        $total_adjustment = $adjustments->sum('total_adjustment');
        $total_recovered = $adjustments->sum('total_recovered');
        $totalTransferShippingCost = $branchTransfer->sum('total_shipping_cost_br') + $warehouseTransfer->sum('total_shipping_cost_wh');
        $purchaseTotalShipmentCost = $purchases->sum('total_shipment_charge');

        $todayProfitParameters = [
            $total_adjustment,
            $total_recovered,
            $totalSales,
            $totalSalesReturn,
            $totalSaleTax,
            $totalExpense,
            $totalTransferShippingCost,
        ];

        $todayProfit = $this->todayProfit(...$todayProfitParameters);

        return view('dashboard.ajax_view.today_summery', compact(
            'totalSales',
            'totalSaleDue',
            'totalReceive',
            'totalSaleDiscount',
            'totalSalesReturn',
            'totalSalesShipmentCost',
            'totalPurchase',
            'totalPurchaseDue',
            'totalPayment',
            'totalPurchaseReturn',
            'totalExpense',
            'total_adjustment',
            'total_recovered',
            'totalTransferShippingCost',
            'purchaseTotalShipmentCost',
            'todayProfit'
        ));
    }

    public function todayProfit($totalAdjust, $totalRecovered, $totalSale, $totalSalesReturn, $totalOrderTax, $totalExpanse, $totalTransferCost)
    {
        $saleProductQuery = DB::table('purchase_sale_product_chains')
            ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
            ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->select(
                DB::raw('SUM(net_unit_cost * sold_qty) as total_unit_cost')
            );

        $saleProducts = $saleProductQuery->where('sales.status', 1)->whereDate('sales.report_date', TODAY_DATE)->get();

        $totalTotalUnitCost = $saleProducts->sum('total_unit_cost');

        return $netProfit = ($totalSale + $totalRecovered)
            - $totalAdjust
            - $totalExpanse
            - $totalSalesReturn
            - $totalOrderTax
            - $totalTotalUnitCost
            - $totalTransferCost;
    }

    public function changeLang($lang)
    {
        session(['lang' => $lang]);

        return redirect()->back();
    }
}
