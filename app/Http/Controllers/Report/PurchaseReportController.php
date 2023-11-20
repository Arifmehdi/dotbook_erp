<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReportController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('purchase_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $purchases = '';

            $query = DB::table('purchases')->where('is_purchased', 1);

            if (! empty($request->warehouse_id)) {

                $query->where('purchases.warehouse_id', $request->warehouse_id);
            }

            if ($request->supplier_account_id) {

                $query->where('purchases.supplier_account_id', $request->supplier_account_id);
            }

            if ($request->user_id) {

                $query->where('purchases.admin_id', $request->user_id);
            }

            if ($request->purchase_account_id) {

                $query->where('purchases.purchase_account_id', $request->purchase_account_id);
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('purchases.report_date', $date_range); // Final
            }

            $purchases = $query->leftJoin('purchase_requisitions', 'purchases.requisition_id', 'purchase_requisitions.id')
                ->leftJoin('departments', 'purchase_requisitions.department_id', 'departments.id')
                ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
                ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
                ->select(
                    'purchases.id',
                    'purchases.warehouse_id',
                    'purchases.date',
                    'purchases.invoice_id',
                    'purchases.purchase_note',
                    'purchases.total_qty',
                    'purchases.net_total_amount',
                    'purchases.order_discount_amount',
                    'purchases.purchase_tax_percent',
                    'purchases.purchase_tax_amount',
                    'purchases.total_purchase_amount',
                    'purchases.total_additional_expense',
                    'purchases.due',
                    'purchases.paid',
                    'purchases.purchase_status',
                    'purchase_requisitions.requisition_no',
                    'departments.name as dep_name',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'suppliers.name as supplier_name',
                )->orderBy('purchases.report_date', 'desc');

            return DataTables::of($purchases)

                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })

                ->editColumn('invoice_id', function ($row) {

                    return '<a href="'.route('purchases.show', [$row->id]).'" id="details_btn">'.$row->invoice_id.'</a>';
                })

                ->editColumn('department', function ($row) {

                    return $row->dep_name ? $row->dep_name : '...';
                })

                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="'.$row->net_total_amount.'">'.\App\Utils\Converter::format_in_bdt($row->net_total_amount).'</span>')

                ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="'.$row->total_qty.'">'.\App\Utils\Converter::format_in_bdt($row->total_qty).'</span>')

                ->editColumn('order_discount_amount', fn ($row) => '<span class="order_discount_amount" data-value="'.$row->order_discount_amount.'">'.\App\Utils\Converter::format_in_bdt($row->order_discount_amount).'</span>')

                ->editColumn('purchase_tax_amount', fn ($row) => '<span class="net_total_amount" data-value="'.$row->purchase_tax_amount.'">'.\App\Utils\Converter::format_in_bdt($row->purchase_tax_amount).'('.$row->purchase_tax_percent.')'.'</span>')

                ->editColumn('total_additional_expense', fn ($row) => '<span class="total_additional_expense" data-value="'.$row->total_additional_expense.'">'.\App\Utils\Converter::format_in_bdt($row->total_additional_expense).'</span>')

                ->editColumn('total_purchase_amount', fn ($row) => '<span class="total_purchase_amount" data-value="'.$row->total_purchase_amount.'">'.\App\Utils\Converter::format_in_bdt($row->total_purchase_amount).'</span>')

                ->rawColumns(['date', 'invoice_id', 'total_qty', 'net_total_amount', 'order_discount_amount', 'purchase_tax_amount', 'total_additional_expense', 'total_purchase_amount'])
                ->make(true);
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $purchaseAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->where('allow_login', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('procurement.reports.purchase_report.index', compact('supplierAccounts', 'purchaseAccounts', 'users'));
    }

    public function print(Request $request)
    {
        if (! auth()->user()->can('purchase_report')) {

            abort(403, 'Access Forbidden.');
        }

        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;
        $userName = $request->user_name;
        $supplierName = $request->supplier_name;
        $purchaseAccountName = $request->purchase_account_name;

        $purchases = '';

        $query = DB::table('purchases')->where('is_purchased', 1);

        if (! empty($request->warehouse_id)) {

            $query->where('purchases.warehouse_id', $request->warehouse_id);
        }

        if ($request->supplier_account_id) {

            $query->where('purchases.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->user_id) {

            $query->where('purchases.admin_id', $request->user_id);
        }

        if ($request->purchase_account_id) {

            $query->where('purchases.purchase_account_id', $request->purchase_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        $purchases = $query->leftJoin('purchase_requisitions', 'purchases.requisition_id', 'purchase_requisitions.id')
            ->leftJoin('departments', 'purchase_requisitions.department_id', 'departments.id')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
            ->select(
                'purchases.id',
                'purchases.warehouse_id',
                'purchases.date',
                'purchases.invoice_id',
                'purchases.purchase_note',
                'purchases.total_item',
                'purchases.total_qty',
                'purchases.net_total_amount',
                'purchases.order_discount_amount',
                'purchases.purchase_tax_percent',
                'purchases.purchase_tax_amount',
                'purchases.total_additional_expense',
                'purchases.total_purchase_amount',
                'purchases.purchase_status',
                'purchase_requisitions.requisition_no',
                'departments.name as dep_name',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'suppliers.name as supplier_name',
            )->orderBy('purchases.report_date', 'desc')->get();

        return view(
            'procurement.reports.purchase_report.ajax_view.print',
            compact(
                'purchases',
                'fromDate',
                'toDate',
                'userName',
                'supplierName',
                'purchaseAccountName',
            )
        );
    }

    public function printSummary(Request $request)
    {
        if (! auth()->user()->can('purchase_report')) {

            abort(403, 'Access Forbidden.');
        }

        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;
        $userName = $request->user_name;
        $supplierName = $request->supplier_name;
        $purchaseAccountName = $request->purchase_account_name;

        $purchases = '';

        $query = DB::table('purchases')->where('is_purchased', 1);

        if (! empty($request->warehouse_id)) {

            $query->where('purchases.warehouse_id', $request->warehouse_id);
        }

        if ($request->supplier_account_id) {

            $query->where('purchases.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->user_id) {

            $query->where('purchases.admin_id', $request->user_id);
        }

        if ($request->purchase_account_id) {

            $query->where('purchases.purchase_account_id', $request->purchase_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        $purchases = $query->select(
            DB::raw('IFNULL(SUM(purchases.total_qty), 0) as total_qty'),
            DB::raw('IFNULL(SUM(purchases.net_total_amount), 0) as total_net_amount'),
            DB::raw('IFNULL(SUM(purchases.order_discount_amount), 0) as total_purchase_discount'),
            DB::raw('IFNULL(SUM(purchases.purchase_tax_amount), 0) as total_tax_amount'),
            DB::raw('IFNULL(SUM(purchases.total_additional_expense), 0) as total_additional_expense'),
            DB::raw('IFNULL(SUM(purchases.total_purchase_amount), 0) as total_purchased_amount'),
        )->groupBy('purchases.id')->get();

        return view(
            'procurement.reports.purchase_report.ajax_view.print_summary',
            compact(
                'purchases',
                'fromDate',
                'toDate',
                'userName',
                'supplierName',
                'purchaseAccountName',
            )
        );
    }
}
