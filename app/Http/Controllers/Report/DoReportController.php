<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DoReportController extends Controller
{
    public $converter;

    public function __construct(
        Converter $converter
    ) {

        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('do_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->select('business')->first();

            $dos = '';

            $query = DB::table('sales')->where('sales.do_status', 1);

            if (auth()->user()->can('view_own_sale')) {

                $query->where('sales.order_by_id', auth()->user()->id);
            }

            $this->filteredQuery($request, $query);

            $dos = $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
                ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')->select(
                    'sales.id',
                    'sales.total_item',
                    'sales.total_do_qty',
                    'sales.total_delivered_qty',
                    'sales.do_total_left_qty',
                    'sales.do_id',
                    'sales.do_date',
                    'sales.net_total_amount',
                    'sales.total_payable_amount',
                    'sales.order_discount_amount',
                    'sales.shipment_charge',
                    'sales.paid',
                    'sales.due',
                    'sales.all_price_type',
                    'customers.name as customer_name',
                    'sr.prefix as u_prefix',
                    'sr.name as u_name',
                    'sr.last_name as u_last_name',
                )->orderBy('sales.do_date', 'desc');

            return DataTables::of($dos)

                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->do_date));
                })

                ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

                ->editColumn('sr', fn ($row) => $row->u_prefix.' '.$row->u_name.' '.$row->u_last_name)

                ->editColumn('total_do_qty', fn ($row) => '<span class="total_do_qty" data-value="'.$row->total_do_qty.'">'.$this->converter->format_in_bdt($row->total_do_qty).'</span>')

                ->editColumn('total_delivered_qty', fn ($row) => '<span class="total_delivered_qty" data-value="'.$row->total_delivered_qty.'">'.$this->converter->format_in_bdt($row->total_delivered_qty).'</span>')

                ->editColumn('do_total_left_qty', fn ($row) => '<span class="do_total_left_qty" data-value="'.$row->do_total_left_qty.'">'.$this->converter->format_in_bdt($row->do_total_left_qty).'</span>')

                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="'.$row->net_total_amount.'">'.$this->converter->format_in_bdt($row->net_total_amount).'</span>')

                ->editColumn('order_discount_amount', fn ($row) => '<span class="order_discount_amount" data-value="'.$row->order_discount_amount.'">'.$this->converter->format_in_bdt($row->order_discount_amount).'</span>')

                ->editColumn('shipment_charge', fn ($row) => '<span class="shipment_charge" data-value="'.$row->shipment_charge.'">'.$this->converter->format_in_bdt($row->shipment_charge).'</span>')

                ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="'.$row->total_payable_amount.'">'.$this->converter->format_in_bdt($row->total_payable_amount).'</span>')

                ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="'.$row->paid.'">'.$this->converter->format_in_bdt($row->paid).'</span>')

                ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="'.$row->due.'">'.$this->converter->format_in_bdt($row->due).'</span>')

                ->rawColumns(['date', 'customer', 'sr', 'total_do_qty', 'total_delivered_qty', 'do_total_left_qty', 'net_total_amount', 'total_payable_amount', 'order_discount_amount', 'order_tax_amount', 'shipment_charge', 'paid', 'due'])
                ->make(true);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->get(['id', 'prefix', 'name', 'last_name', 'phone']);

        return view('sales_app.reports.do_report.index', compact('customerAccounts', 'users'));
    }

    public function print(Request $request)
    {
        if (! auth()->user()->can('do_report')) {

            abort(403, 'Access Forbidden.');
        }

        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $customerName = $request->customer_name;
        $userName = $request->user_name;

        $dos = '';

        $query = DB::table('sales')->where('sales.do_status', 1);

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.order_by_id', auth()->user()->id);
        }

        $this->filteredQuery($request, $query);

        $dos = $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')->select(
                'sales.id',
                'sales.total_do_qty',
                'sales.total_delivered_qty',
                'sales.do_total_left_qty',
                'sales.do_id',
                'sales.do_date',
                'sales.net_total_amount',
                'sales.total_payable_amount',
                'sales.sale_return_amount',
                'sales.order_discount_amount',
                'sales.shipment_charge',
                'sales.paid',
                'sales.due',
                'sales.all_price_type',
                'customers.name as customer_name',
                'sr.prefix as u_prefix',
                'sr.name as u_name',
                'sr.last_name as u_last_name',
            )->orderBy('sales.do_date', 'desc')->get();

        return view(
            'sales_app.reports.do_report.ajax_view.print',
            compact(
                'dos',
                'fromDate',
                'toDate',
                'customerName',
                'userName',
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

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.do_date', $date_range); // Final
        }

        return $query;
    }
}
