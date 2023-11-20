<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaleReturnReportController extends Controller
{
    public function __construct(private Converter $converter)
    {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('sales_return_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();

            $returns = '';

            $query = DB::table('sale_returns')
                ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
                ->leftJoin('accounts as customers', 'sale_returns.customer_account_id', 'customers.id')
                ->leftJoin('users as createdBy', 'sale_returns.created_by_id', 'createdBy.id');

            $query->select(
                'sale_returns.id',
                'sale_returns.total_item',
                'sale_returns.total_qty',
                'sale_returns.voucher_no',
                'sale_returns.date',
                'sale_returns.net_total_amount',
                'sale_returns.return_discount_amount',
                'sale_returns.return_tax_percent',
                'sale_returns.return_tax_amount',
                'sale_returns.total_return_amount',
                'sales.invoice_id as parent_sale',
                'customers.name as customer_name',
                'createdBy.prefix as u_prefix',
                'createdBy.name as u_name',
                'createdBy.last_name as u_last_name',
            );

            $returns = $this->filteredQuery($request, $query)->orderBy('sale_returns.report_date', 'desc');

            return DataTables::of($returns)

                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->date));
                })

                ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

                ->editColumn('createdBy', fn ($row) => $row->u_prefix.' '.$row->u_name.' '.$row->u_last_name)

                ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="'.$row->total_item.'">'.$this->converter->format_in_bdt($row->total_item).'</span>')

                ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="'.$row->total_qty.'">'.$this->converter->format_in_bdt($row->total_qty).'</span>')

                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="'.$row->net_total_amount.'">'.$this->converter->format_in_bdt($row->net_total_amount).'</span>')

                ->editColumn('return_discount_amount', fn ($row) => '<span class="return_discount_amount" data-value="'.$row->return_discount_amount.'">'.$this->converter->format_in_bdt($row->return_discount_amount).'</span>')

                ->editColumn('return_tax_amount', fn ($row) => '<span class="return_tax_amount" data-value="'.$row->return_tax_amount.'">'.'('.$row->return_tax_percent.'%)='.$this->converter->format_in_bdt($row->return_tax_amount).'</span>')

                ->editColumn('total_return_amount', fn ($row) => '<span class="total_return_amount text-danger" data-value="'.$row->total_return_amount.'">'.$this->converter->format_in_bdt($row->total_return_amount).'</span>')

                ->rawColumns(['date', 'invoice_id', 'customer', 'createdBy', 'total_item', 'total_qty', 'net_total_amount', 'return_discount_amount', 'return_tax_amount', 'total_return_amount', 'total_return_amount', 'total_return_due_pay'])
                ->make(true);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view('sales_app.reports.sales_return_report.index', compact('customerAccounts'));
    }

    public function print(Request $request)
    {
        if (! auth()->user()->can('sales_return_report')) {

            abort(403, 'Access Forbidden.');
        }

        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;
        $returns = '';

        $query = DB::table('sale_returns')
            ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
            ->leftJoin('accounts as customers', 'sale_returns.customer_account_id', 'customers.id')
            ->leftJoin('users', 'sale_returns.created_by_id', 'users.id');

        $query->select(
            'sale_returns.id',
            'sale_returns.total_item',
            'sale_returns.total_qty',
            'sale_returns.voucher_no',
            'sale_returns.date',
            'sale_returns.net_total_amount',
            'sale_returns.return_discount_amount',
            'sale_returns.return_tax_percent',
            'sale_returns.return_tax_amount',
            'sale_returns.total_return_amount',
            'sales.invoice_id as parent_sale',
            'customers.name as customer_name',
            'users.prefix as u_prefix',
            'users.name as u_name',
            'users.last_name as u_last_name',
        );

        $returns = $this->filteredQuery($request, $query)->orderBy('sale_returns.report_date', 'desc')->get();

        return view('sales_app.reports.sales_return_report.ajax_view.print', compact('returns', 'fromDate', 'toDate'));
    }

    private function filteredQuery($request, $query)
    {
        if ($request->user_id) {

            $query->where('sale_returns.sr_user_id', $request->user_id);
        }

        if ($request->customer_account_id) {

            $query->where('sale_returns.customer_account_id', $request->customer_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sale_returns.report_date', $date_range); // Final
        }

        return $query;
    }
}
