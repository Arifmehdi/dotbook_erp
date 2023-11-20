<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesOrderReportUserWiseController extends Controller
{
    public $converter;

    public function __construct(Converter $converter)
    {

        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('sr_wise_order_report')) {

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
                ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')
                ->select(
                    'sales.id',
                    'sales.order_by_id',
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
                )->orderBy('sales.report_date', 'desc');

            return DataTables::of($orders)

                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->order_date));
                })

                ->editColumn('customer', fn ($row) => $row->customer_name)

                ->editColumn('sr', fn ($row) => '<strong>'.ucfirst($row->u_prefix.$row->u_name.$row->u_last_name).'</strong>')

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

        $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        $saleAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->get(['accounts.id', 'accounts.name']);

        return view('sales_app.reports.sales_order_report_sr_wise.index', compact('customerAccounts', 'users', 'saleAccounts'));
    }

    public function print(Request $request)
    {
        if (! auth()->user()->can('sr_wise_order_report')) {

            abort(403, 'Access Forbidden.');
        }

        $user_id = $request->user_id;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $customerName = $request->customer_name;
        $saleAccountName = $request->sale_account_name;
        $userName = $request->user_name;

        $sales = '';

        $query = DB::table('sales')->where('sales.order_status', 1);

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.order_by_id', auth()->user()->id);
        }

        $this->filteredQuery($request, $query);

        $sales = $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')->select(
                'sales.id',
                'sales.sr_user_id',
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
                'sr.phone as u_phone',
            )
            ->orderBy('sales.sr_user_id')
            ->orderBy('sales.order_date', 'desc')
            ->get();

        $count = count($sales);
        $veryLastUserId = $count > 0 ? $sales->last()->sr_user_id : '';
        $lastRow = $count - 1;

        return view('sales_app.reports.sales_order_report_sr_wise.ajax_view.print', compact(
            'sales',
            'fromDate',
            'toDate',
            'customerName',
            'userName',
            'saleAccountName',
            'count',
            'veryLastUserId',
            'lastRow',
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
            $query->whereBetween('sales.order_date', $date_range); // Final
        }

        return $query;
    }
}
