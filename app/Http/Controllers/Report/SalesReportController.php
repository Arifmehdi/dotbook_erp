<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesReportController extends Controller
{
    public function __construct(private Converter $converter)
    {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('sales_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $converter = $this->converter;
            $generalSettings = DB::table('general_settings')->first();

            $sales = '';

            $query = DB::table('sales');

            if (auth()->user()->can('view_own_sale')) {

                $query->where('sales.sale_by_id', auth()->user()->id);
            }

            $this->filteredQuery($request, $query);

            $sales = $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
                ->leftJoin('sales as do', 'sales.delivery_order_id', 'do.id')
                ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')
                ->leftJoin('weight_scales', 'sales.id', 'weight_scales.sale_id')
                ->select(
                    'sales.id',
                    'sales.invoice_id',
                    'sales.do_to_inv_challan_no',
                    'sales.date',
                    'sales.net_total_amount',
                    'sales.total_payable_amount',
                    'sales.total_sold_qty',
                    'sales.order_discount_amount',
                    'sales.order_tax_percent',
                    'sales.order_tax_amount',
                    'sales.shipment_charge',
                    'customers.name as customer_name',
                    'weight_scales.first_weight',
                    'weight_scales.second_weight',
                    'do.do_id',
                    'sr.prefix as sr_prefix',
                    'sr.name as sr_name',
                    'sr.last_name as sr_last_name',
                )->where('sales.status', 1)->orderBy('sales.report_date', 'desc');

            return DataTables::of($sales)

                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->date));
                })

                ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

                ->editColumn('sr', fn ($row) => $row->sr_prefix.' '.$row->sr_name.' '.$row->sr_last_name)

                ->editColumn('total_sold_qty', fn ($row) => '<span class="total_sold_qty" data-value="'.$row->total_sold_qty.'">'.$this->converter->format_in_bdt($row->total_sold_qty).'</span>')

                ->editColumn('net_weight', function ($row) use ($converter) {

                    if ($row->first_weight) {

                        $netWeight = $row->second_weight - $row->first_weight;

                        return '<span class="net_weight" data-value="'.$netWeight.'">'.$converter->format_in_bdt($netWeight).'</span>';
                    }
                })

                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="'.$row->net_total_amount.'">'.$this->converter->format_in_bdt($row->net_total_amount).'</span>')

                ->editColumn('order_discount_amount', fn ($row) => '<span class="order_discount_amount" data-value="'.$row->order_discount_amount.'">'.$this->converter->format_in_bdt($row->order_discount_amount).'</span>')

                ->editColumn('order_tax_amount', fn ($row) => '<span class="order_tax_amount" data-value="'.$row->order_tax_amount.'">'.$this->converter->format_in_bdt($row->order_tax_amount).'('.$row->order_tax_percent.'%)'.'</span>')

                ->editColumn('shipment_charge', fn ($row) => '<span class="shipment_charge" data-value="'.$row->shipment_charge.'">'.$this->converter->format_in_bdt($row->shipment_charge).'</span>')

                ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="'.$row->total_payable_amount.'">'.$this->converter->format_in_bdt($row->total_payable_amount).'</span>')

                ->rawColumns(['date', 'invoice_id', 'customer', 'sr', 'total_sold_qty', 'net_weight', 'net_total_amount', 'total_payable_amount', 'order_discount_amount', 'order_tax_amount', 'shipment_charge'])
                ->make(true);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        $saleAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->get(['accounts.id', 'accounts.name']);

        return view('sales_app.reports.sales_report.index', compact('customerAccounts', 'saleAccounts', 'users'));
    }

    public function print(Request $request)
    {
        if (! auth()->user()->can('sales_report')) {

            abort(403, 'Access Forbidden.');
        }

        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;
        $userName = $request->user_name;
        $customerName = $request->customer_name;
        $saleAccountName = $request->sale_account_name;
        $sales = '';

        $query = DB::table('sales');

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.sale_by_id', auth()->user()->id);
        }

        $this->filteredQuery($request, $query);

        $sales = $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('sales as do', 'sales.delivery_order_id', 'do.id')
            ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')
            ->leftJoin('weight_scales', 'sales.id', 'weight_scales.sale_id')
            ->select(
                'sales.id',
                'sales.invoice_id',
                'sales.do_to_inv_challan_no',
                'sales.date',
                'sales.net_total_amount',
                'sales.total_payable_amount',
                'sales.total_sold_qty',
                'sales.order_discount_amount',
                'sales.order_tax_percent',
                'sales.order_tax_amount',
                'sales.shipment_charge',
                'customers.name as customer_name',
                'weight_scales.first_weight',
                'weight_scales.second_weight',
                'do.do_id',
                'sr.prefix as sr_prefix',
                'sr.name as sr_name',
                'sr.last_name as sr_last_name',
            )->where('sales.status', 1)
            ->orderBy('sales.report_date', 'desc')->get();

        return view('sales_app.reports.sales_report.ajax_view.print', compact(
            'sales',
            'fromDate',
            'toDate',
            'userName',
            'customerName',
            'saleAccountName',
        ));
    }

    public function printSummary(Request $request)
    {
        if (! auth()->user()->can('sales_report')) {

            abort(403, 'Access Forbidden.');
        }

        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;
        $userName = $request->user_name;
        $customerName = $request->customer_name;
        $saleAccountName = $request->sale_account_name;
        $sales = '';

        $query = DB::table('sales');

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.sale_by_id', auth()->user()->id);
        }

        $this->filteredQuery($request, $query);

        $sales = $query->leftJoin('weight_scales', 'sales.id', 'weight_scales.sale_id')
            ->select(
                DB::raw('IFNULL(SUM(weight_scales.second_weight - weight_scales.first_weight), 0) as total_weight'),
                DB::raw('IFNULL(SUM(sales.total_sold_qty), 0) as total_qty'),
                DB::raw('IFNULL(SUM(sales.net_total_amount), 0) as total_net_amount'),
                DB::raw('IFNULL(SUM(sales.order_discount_amount), 0) as total_sale_discount'),
                DB::raw('IFNULL(SUM(sales.order_tax_amount), 0) as total_tax_amount'),
                DB::raw('IFNULL(SUM(sales.shipment_charge), 0) as total_shipment_charge'),
                DB::raw('IFNULL(SUM(sales.total_payable_amount), 0) as total_sold_amount'),
            )->where('sales.status', 1)->groupBy('sales.id', 'weight_scales.id')->get();

        return view('sales_app.reports.sales_report.ajax_view.print_summary', compact(
            'sales',
            'fromDate',
            'toDate',
            'userName',
            'customerName',
            'saleAccountName',
        ));
    }

    private function filteredQuery($request, $query)
    {
        if ($request->user_id) {

            $query->where('sales.sr_user_id', $request->user_id);
        }

        if ($request->customer_account_id) {

            $query->where('sales.customer_account_id', $request->customer_account_id);
        }

        if ($request->sale_account_id) {

            $query->where('sales.sale_account_id', $request->sale_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.report_date', $date_range); // Final
        }

        return $query;
    }
}
