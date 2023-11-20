<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesOrderReportController extends Controller
{
    public $converter;

    public function __construct(
        Converter $converter
    ) {

        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('sales_order_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->select('business')->first();

            $orders = '';

            $query = DB::table('sales')->where('sales.order_status', 1);

            if (auth()->user()->can('view_own_sale')) {

                $query->where('sales.order_by_id', auth()->user()->id);
            }

            $this->filteredQuery($request, $query);

            $orders = $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
                ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')->select(
                    'sales.id',
                    'sales.total_item',
                    'sales.total_ordered_qty',
                    'sales.order_id',
                    'sales.order_date',
                    'sales.net_total_amount',
                    'sales.total_payable_amount',
                    'sales.sale_return_amount',
                    'sales.order_discount_amount',
                    'sales.order_tax_percent',
                    'sales.order_tax_amount',
                    'sales.shipment_charge',
                    'sales.paid',
                    'sales.due',
                    'sales.all_price_type',
                    'customers.name as customer_name',
                    'sr.prefix as sr_prefix',
                    'sr.name as sr_name',
                    'sr.last_name as sr_last_name',
                )->orderBy('sales.order_date', 'desc');

            return DataTables::of($orders)

                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->order_date));
                })

                ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

                ->editColumn('sr', fn ($row) => $row->sr_prefix.' '.$row->sr_name.' '.$row->sr_last_name)

                ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="'.$row->total_item.'">'.$this->converter->format_in_bdt($row->total_item).'</span>')

                ->editColumn('total_ordered_qty', fn ($row) => '<span class="total_ordered_qty" data-value="'.$row->total_ordered_qty.'">'.$this->converter->format_in_bdt($row->total_ordered_qty).'</span>')

                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="'.$row->net_total_amount.'">'.$this->converter->format_in_bdt($row->net_total_amount).'</span>')

                ->editColumn('order_discount_amount', fn ($row) => '<span class="order_discount_amount" data-value="'.$row->order_discount_amount.'">'.$this->converter->format_in_bdt($row->order_discount_amount).'</span>')

                ->editColumn('order_tax_amount', fn ($row) => '<span class="order_tax_amount" data-value="'.$row->order_tax_amount.'">'.$this->converter->format_in_bdt($row->order_tax_amount).'('.$row->order_tax_percent.'%)'.'</span>')

                ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="'.$row->total_payable_amount.'">'.$this->converter->format_in_bdt($row->total_payable_amount).'</span>')

                ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="'.$row->paid.'">'.$this->converter->format_in_bdt($row->paid).'</span>')

                ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="'.$row->due.'">'.$this->converter->format_in_bdt($row->due).'</span>')

                ->rawColumns(['date', 'customer', 'sr', 'total_item', 'total_ordered_qty', 'net_total_amount', 'total_payable_amount', 'order_discount_amount', 'order_tax_amount', 'shipment_charge', 'paid', 'due', 'sale_return_amount'])
                ->make(true);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $saleAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->get(['accounts.id', 'accounts.name']);

        $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('sales_app.reports.sales_order_report.index', compact('customerAccounts', 'saleAccounts', 'users'));
    }

    public function print(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $customerName = $request->customer_name;
        $userName = $request->user_name;
        $saleAccountName = $request->sale_account_name;

        $sales = '';

        $query = DB::table('sales')->where('sales.order_status', 1);

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.order_by_id', auth()->user()->id);
        }

        $this->filteredQuery($request, $query);

        $sales = $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')->select(
                'sales.id',
                'sales.total_item',
                'sales.total_ordered_qty',
                'sales.order_id',
                'sales.order_date',
                'sales.net_total_amount',
                'sales.total_payable_amount',
                'sales.sale_return_amount',
                'sales.order_discount_amount',
                'sales.order_tax_percent',
                'sales.order_tax_amount',
                'sales.shipment_charge',
                'sales.paid',
                'sales.due',
                'sales.all_price_type',
                'customers.name as customer_name',
                'sr.prefix as u_prefix',
                'sr.name as u_name',
                'sr.last_name as u_last_name',
            )->orderBy('sales.order_date', 'desc')->get();

        return view(
            'sales_app.reports.sales_order_report.ajax_view.print',
            compact(
                'sales',
                'fromDate',
                'toDate',
                'customerName',
                'userName',
                'saleAccountName',
            )
        );
    }

    public function printWithItem(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $customerName = $request->customer_name;
        $userName = $request->user_name;

        $sales = '';

        $query = DB::table('sales')->where('sales.order_status', 1)
            ->leftJoin('accounts as customersAc', 'sales.customer_account_id', 'customersAc.id')
            ->leftJoin('customers', 'customersAc.customer_id', 'customers.id')
            ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')
            ->leftJoin('sale_products', 'sales.id', 'sale_products.sale_id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id');

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.order_by_id', auth()->user()->id);
        }

        $this->filteredQuery($request, $query);

        $sales = $query->select(
            'sales.id',
            'sales.customer_account_id as cus_id',
            'sales.total_item',
            'sales.total_ordered_qty',
            'sales.order_id',
            'sales.order_date',
            'sales.net_total_amount',
            'sales.total_payable_amount',
            'sales.sale_return_amount',
            'sales.order_discount_amount',
            'sales.order_tax_percent',
            'sales.order_tax_amount',
            'sales.shipment_charge',
            'sales.paid',
            'sales.due',
            'sales.all_price_type',
            'sales.sale_note',
            'sales.comment',
            'sales.payment_note',
            'customersAc.name as customer_name',
            'customers.customer_type',
            'customers.credit_limit',
            'sr.id as u_id',
            'sr.prefix as u_prefix',
            'sr.name as u_name',
            'sr.last_name as u_last_name',
            'sale_products.price_type as item_price_type',
            'sale_products.ordered_quantity',
            'sale_products.unit_price_inc_tax as item_price',
            'sale_products.subtotal as item_subtotal',
            'products.name as p_name',
        )->orderBy('sales.sr_user_id', 'asc')->orderBy('sales.order_date', 'desc')->get();

        return view(
            'sales_app.reports.sales_order_report.ajax_view.print_with_items',
            compact(
                'sales',
                'fromDate',
                'toDate',
                'customerName',
                'userName',
            )
        );
    }

    public function printSummary(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $customerName = $request->customer_name;
        $userName = $request->user_name;
        $saleAccountName = $request->sale_account_name;

        $sales = '';

        $query = DB::table('sales')->where('sales.order_status', 1);

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.order_by_id', auth()->user()->id);
        }

        $this->filteredQuery($request, $query);

        $sales = $query->select(
            DB::raw('IFNULL(SUM(sales.total_ordered_qty), 0) as total_qty'),
            DB::raw('IFNULL(SUM(sales.net_total_amount), 0) as total_net_amount'),
            DB::raw('IFNULL(SUM(sales.order_discount_amount), 0) as total_order_discount'),
            DB::raw('IFNULL(SUM(sales.order_tax_amount), 0) as total_tax_amount'),
            DB::raw('IFNULL(SUM(sales.shipment_charge), 0) as total_shipment_charge'),
            DB::raw('IFNULL(SUM(sales.total_payable_amount), 0) as total_ordered_amount'),
        )->groupBy('sales.id')->get();

        return view(
            'sales_app.reports.sales_order_report.ajax_view.print_summary',
            compact(
                'sales',
                'fromDate',
                'toDate',
                'customerName',
                'userName',
                'saleAccountName',
            )
        );
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
            $query->whereBetween('sales.order_date', $date_range); // Final
        }

        return $query;
    }
}
