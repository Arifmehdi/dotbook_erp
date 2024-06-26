<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalePurchaseReportController extends Controller
{
    public function index()
    {
        return view('procurement.reports.sale_purchase_report.index');
    }

    // Get sale purchase amounts **requested by ajax**
    public function salePurchaseAmounts()
    {
        $sales = '';
        $purchases = '';
        $sales = DB::table('sales')->where('sales.status', 1)->get();
        $purchases = DB::table('purchases')->get();

        return view('procurement.reports.sale_purchase_report.ajax_view.sale_and_purchase_amount', compact('sales', 'purchases'));
    }

    // Get sale purchase amounts **requested by ajax**
    public function filterSalePurchaseAmounts(Request $request)
    {
        $sales = '';
        $purchases = '';
        $sale_query = DB::table('sales');
        $purchase_query = DB::table('purchases');

        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $sale_query->whereBetween('report_date', $date_range);
            $purchase_query->whereBetween('report_date', $date_range);
        }

        $sales = $sale_query->where('sales.status', 1)->get();
        $purchases = $purchase_query->get();

        return view('procurement.reports.sale_purchase_report.ajax_view.filtered_sale_and_purchase_amount', compact('sales', 'purchases'));
    }

    public function printSalePurchase(Request $request)
    {
        $fromDate = '';
        $toDate = '';

        $sales = '';
        $purchases = '';
        $sale_query = DB::table('sales');
        $purchase_query = DB::table('purchases');

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $sale_query->whereBetween('report_date', $date_range);
            $purchase_query->whereBetween('report_date', $date_range);
        }

        $sales = $sale_query->where('sales.status', 1)->get();
        $purchases = $purchase_query->get();

        return view('procurement.reports.sale_purchase_report.ajax_view.printSalePurchase', compact('sales', 'purchases', 'fromDate', 'toDate'));
    }
}
