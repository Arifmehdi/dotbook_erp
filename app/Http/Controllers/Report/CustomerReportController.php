<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\UserWiseCustomerAmountUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CustomerReportController extends Controller
{
    protected $userWiseCustomerAmountUtil;

    public function __construct(UserWiseCustomerAmountUtil $userWiseCustomerAmountUtil)
    {
        $this->userWiseCustomerAmountUtil = $userWiseCustomerAmountUtil;
    }

    // Index view of supplier report
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $userWiseCustomerAmounts = $this->userWiseCustomerAmountUtil;

            $generalSettings = DB::table('general_settings')->first();

            $customers = '';

            $query = DB::table('customers')->where('status', 1)
                ->leftJoin('accounts', 'customers.id', 'accounts.customer_id');

            if ($request->customer_id) {

                $query->where('customers.id', $request->customer_id);
            }

            $customers = $query->select(
                'customers.id',
                'customers.name',
                'customers.phone',
                'customers.address',
                'accounts.id as customer_account_id',
            )->orderBy('customers.name', 'asc');

            return DataTables::of($customers)
                ->editColumn('name', function ($row) {
                    return $row->name.'(<b>'.$row->phone.'</b>)';
                })

                ->editColumn('opening_balance', function ($row) use ($userWiseCustomerAmounts, $request) {

                    $amounts = $userWiseCustomerAmounts->userWiseCustomerAmountSummery($row->id, $row->customer_account_id, $request->user_id, openingBlAndCrLimit: false);
                    $openingBalance = $amounts['opening_balance'];

                    $formattedAmount = \App\Utils\Converter::format_in_bdt($openingBalance);

                    $showAmount = $formattedAmount < 0 ? Str::of($formattedAmount)->replace('-', '')->wrap('(', ')') : $formattedAmount;

                    return '<span class="opening_balance" data-value="'.$openingBalance.'">'.$showAmount.'</span>';
                })

                ->editColumn('total_paid', function ($row) use ($userWiseCustomerAmounts, $request) {

                    $amounts = $userWiseCustomerAmounts->userWiseCustomerAmountSummery($row->id, $row->customer_account_id, $request->user_id, openingBlAndCrLimit: false);
                    $total_received = $amounts['total_received'];

                    $formattedAmount = \App\Utils\Converter::format_in_bdt($total_received);

                    return '<span class="total_paid" data-value="'.$total_received.'">'.$formattedAmount.'</span>';
                })

                ->editColumn('total_sale_due', function ($row) use ($userWiseCustomerAmounts, $request) {

                    $amounts = $userWiseCustomerAmounts->userWiseCustomerAmountSummery($row->id, $row->customer_account_id, $request->user_id, openingBlAndCrLimit: false);
                    $total_sale_due = $amounts['total_sale_due'];

                    $formattedAmount = \App\Utils\Converter::format_in_bdt($total_sale_due);

                    $showAmount = $formattedAmount < 0 ? Str::of($formattedAmount)->replace('-', '')->wrap('(', ')') : $formattedAmount;

                    return '<span class="total_sale_due" data-value="'.$total_sale_due.'">'.$showAmount.'</span>';
                })

                ->editColumn('total_sale', function ($row) use ($userWiseCustomerAmounts, $request) {

                    $amounts = $userWiseCustomerAmounts->userWiseCustomerAmountSummery($row->id, $row->customer_account_id, $request->user_id, openingBlAndCrLimit: false);
                    $total_sale = $amounts['total_sale'];

                    $formattedAmount = \App\Utils\Converter::format_in_bdt($total_sale);

                    return '<span class="total_sale" data-value="'.$total_sale.'">'.$formattedAmount.'</span>';
                })

                ->editColumn('total_sale_return_due', function ($row) use ($userWiseCustomerAmounts, $request) {

                    $amounts = $userWiseCustomerAmounts->userWiseCustomerAmountSummery($row->id, $row->customer_account_id, $request->user_id, openingBlAndCrLimit: false);
                    $total_sale_return_due = $amounts['total_sale_return_due'];

                    $formattedAmount = \App\Utils\Converter::format_in_bdt($total_sale_return_due);

                    return '<span class="total_sale_return_due" data-value="'.$total_sale_return_due.'">'.$formattedAmount.'</span>';
                })

                ->rawColumns(['name', 'opening_balance', 'total_paid', 'total_sale', 'total_sale_due', 'total_due', 'total_sale_return_due'])
                ->make(true);
        }

        $users = DB::table('users')->where('allow_login', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        $customers = DB::table('customers')->select('id', 'name', 'phone')->get();

        return view('sales_app.reports.customer_report.index', compact('customers', 'users'));
    }

    public function print(Request $request)
    {
        $customerReports = '';
        $user_id = $request->user_id;

        $query = DB::table('customers')->where('status', 1)
            ->leftJoin('accounts', 'customers.id', 'accounts.customer_id');

        if ($request->customer_id) {

            $query->where('customers.id', $request->customer_id);
        }

        $customerReports = $query->select(
            'customers.id',
            'customers.name',
            // 'customers.contact_id',
            'customers.phone',
            'customers.address',
            'accounts.id as customer_account_id',
        )->orderBy('customers.name', 'asc')->get();

        return view('sales_app.reports.customer_report.ajax_view.print', compact('customerReports', 'user_id'));
    }
}
