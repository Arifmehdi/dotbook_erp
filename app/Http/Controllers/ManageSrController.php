<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Converter;
use App\Utils\CustomerUtil;
use App\Utils\SrUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageSrController extends Controller
{
    protected $customerUtil;

    protected $srUtil;

    protected $converter;

    public function __construct(
        CustomerUtil $customerUtil,
        SrUtil $srUtil,
        Converter $converter
    ) {
        $this->customerUtil = $customerUtil;
        $this->srUtil = $srUtil;
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('manage_sr_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->srUtil->srListTable($request);
        }

        return view('sales_app.manage_sr.index');
    }

    public function create()
    {
        if (! auth()->user()->can('user_add') && ! auth()->user()->can('manage_sr_create')) {

            abort(403, 'Access Forbidden.');
        }

        $isSr = true;

        return view('users.create', compact('isSr'));
    }

    public function manage(Request $request, $id)
    {
        if (! auth()->user()->can('manage_sr_manage')) {

            abort(403, 'Access Forbidden.');
        }

        $user = User::with(['roles'])->where('id', $id)->firstOrFail();

        $accountIds = DB::table('account_ledgers')->where('account_ledgers.user_id', $id)
            ->select('account_ledgers.account_id')->distinct()->pluck('account_ledgers.account_id');

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->whereIn('accounts.id', $accountIds)
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view('sales_app.manage_sr.manage', compact('customerAccounts', 'user'));
    }

    public function printSrOrders(Request $request, $id)
    {
        if (! auth()->user()->can('manage_sr_manage')) {

            abort(403, 'Access Forbidden.');
        }

        $orders = '';

        $customerName = $request->customer_name;
        $fromDate = '';
        $toDate = '';

        $query = DB::table('sales')->where('sales.order_status', 1)
            ->where('sales.sr_user_id', $id);

        if ($request->customer_account_id) {

            $query->where('sales.customer_account_id', $request->customer_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.order_date', $date_range); // Final
        }

        $orders = $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('users', 'sales.sr_user_id', 'users.id')->select(
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
                'users.prefix as u_prefix',
                'users.name as u_name',
                'users.last_name as u_last_name',
            )->orderBy('sales.order_date', 'desc')->get();

        $user = DB::table('users')->where('id', $id)->select('id', 'prefix', 'name', 'last_name', 'phone')->first();

        return view(
            'sales_app.manage_sr.ajax_view.print_sr_orders',
            compact(
                'orders',
                'fromDate',
                'toDate',
                'customerName',
                'user',
            )
        );
    }

    public function printSrSales(Request $request, $id)
    {
        if (! auth()->user()->can('manage_sr_manage')) {

            abort(403, 'Access Forbidden.');
        }

        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;
        $customerName = $request->customer_name;
        $sales = '';

        $query = DB::table('sales')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('users', 'sales.sr_user_id', 'users.id')
            ->leftJoin('sales as do', 'sales.delivery_order_id', 'do.id')
            ->where('sales.status', 1)
            ->where('sales.sr_user_id', $id);

        if ($request->customer_account_id) {

            $query->where('sales.customer_account_id', $request->customer_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.report_date', $date_range); // Final
        }

        $sales = $query->select(
            'sales.id',
            'sales.total_item',
            'sales.invoice_id',
            'sales.date',
            'sales.net_total_amount',
            'sales.total_payable_amount',
            'sales.sale_return_amount',
            'sales.order_discount_amount',
            'sales.order_tax_percent',
            'sales.order_tax_amount',
            'sales.shipment_charge',
            'sales.paid',
            'sales.due',
            'do.do_id',
            'customers.name as customer_name',
            'users.prefix as u_prefix',
            'users.name as u_name',
            'users.last_name as u_last_name',
        )->orderBy('sales.report_date', 'desc')->get();

        $user = DB::table('users')->where('id', $id)->select('id', 'prefix', 'name', 'last_name', 'phone')->first();

        return view('sales_app.manage_sr.ajax_view.print_sr_sales', compact(
            'sales',
            'fromDate',
            'toDate',
            'customerName',
            'user'
        ));
    }

    public function srClosingBalance(Request $request, $user_id)
    {
        return $this->srUtil->srClosingBalance($user_id, $request->customer_account_id, $request->from_date, $request->to_date);
    }
}
