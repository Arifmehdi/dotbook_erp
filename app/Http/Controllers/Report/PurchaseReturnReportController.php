<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $purchaseReturns = '';

            $query = DB::table('purchase_returns');

            if ($request->supplier_account_id) {

                $query->where('purchase_returns.supplier_account_id', $request->supplier_account_id);
            }

            if ($request->user_id) {

                $query->where('purchase_returns.created_by_id', $request->user_id);
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('purchase_returns.report_date', $date_range);
            }

            $purchaseReturns = $query
                ->leftJoin('purchases', 'purchase_returns.purchase_id', 'purchases.id')
                ->leftJoin('accounts as suppliers', 'purchase_returns.supplier_account_id', 'suppliers.id')
                ->leftJoin('users', 'purchase_returns.created_by_id', 'users.id')->select(
                    'purchase_returns.id',
                    'purchase_returns.voucher_no',
                    'purchase_returns.total_item',
                    'purchase_returns.total_qty',
                    'purchase_returns.net_total_amount',
                    'purchase_returns.return_discount',
                    'purchase_returns.return_discount_type',
                    'purchase_returns.return_discount_amount',
                    'purchase_returns.return_tax_percent',
                    'purchase_returns.return_tax_amount',
                    'purchase_returns.total_return_amount',
                    'purchase_returns.date',
                    'purchase_returns.report_date',
                    'purchases.id as purchase_id',
                    'purchases.invoice_id as purchase_invoice_id',
                    'suppliers.name as supplier_name',
                    'users.prefix as created_prefix',
                    'users.name as created_name',
                    'users.last_name as created_last_name',
                )->orderBy('purchase_returns.report_date', 'desc');

            return DataTables::of($purchaseReturns)

                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })

                ->editColumn('created_by', function ($row) {

                    return $row->created_prefix.' '.$row->created_name.' '.$row->created_last_name;
                })

                ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="'.$row->total_item.'">'.\App\Utils\Converter::format_in_bdt($row->total_item).'</span>')

                ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="'.$row->total_qty.'">'.\App\Utils\Converter::format_in_bdt($row->total_qty).'</span>')

                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="'.$row->net_total_amount.'">'.\App\Utils\Converter::format_in_bdt($row->net_total_amount).'</span>')

                ->editColumn('return_discount_amount', fn ($row) => '<span class="return_discount_amount" data-value="'.$row->return_discount_amount.'">'.\App\Utils\Converter::format_in_bdt($row->return_discount_amount).'</span>')

                ->editColumn('return_tax_amount', fn ($row) => '<span class="return_tax_amount" data-value="'.$row->return_tax_amount.'">'.'('.$row->return_tax_percent.'%)='.\App\Utils\Converter::format_in_bdt($row->return_tax_amount).'</span>')

                ->editColumn('total_return_amount', fn ($row) => '<span class="total_return_amount" data-value="'.$row->total_return_amount.'">'.\App\Utils\Converter::format_in_bdt($row->total_return_amount).'</span>')

                ->rawColumns(['date', 'created_by', 'status', 'total_item', 'total_qty', 'net_total_amount', 'return_discount_amount', 'return_tax_amount', 'total_return_amount'])
                ->make(true);
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->where('allow_login', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('procurement.reports.purchase_return_report.index', compact('supplierAccounts', 'users'));
    }

    public function print(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;
        $userName = $request->user_name;
        $supplierName = $request->supplier_name;
        $paymentStatus = $request->payment_status;

        $returns = '';

        $query = DB::table('purchase_returns');

        if ($request->supplier_account_id) {

            $query->where('purchase_returns.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->user_id) {

            $query->where('purchase_returns.created_by_id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchase_returns.report_date', $date_range);
        }

        $returns = $query
            ->leftJoin('purchases', 'purchase_returns.purchase_id', 'purchases.id')
            ->leftJoin('accounts as suppliers', 'purchase_returns.supplier_account_id', 'suppliers.id')
            ->leftJoin('users', 'purchase_returns.created_by_id', 'users.id')->select(
                'purchase_returns.id',
                'purchase_returns.voucher_no',
                'purchase_returns.total_item',
                'purchase_returns.total_qty',
                'purchase_returns.net_total_amount',
                'purchase_returns.return_discount',
                'purchase_returns.return_discount_type',
                'purchase_returns.return_discount_amount',
                'purchase_returns.return_tax_percent',
                'purchase_returns.return_tax_amount',
                'purchase_returns.total_return_amount',
                'purchase_returns.date',
                'purchase_returns.report_date',
                'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_invoice_id',
                'suppliers.name as supplier_name',
                'users.prefix as created_prefix',
                'users.name as created_name',
                'users.last_name as created_last_name',
            )->orderBy('purchase_returns.report_date', 'desc')->get();

        return view(
            'procurement.reports.purchase_return_report.ajax_view.print',
            compact(
                'returns',
                'fromDate',
                'toDate',
                'userName',
                'supplierName',
            )
        );
    }
}
