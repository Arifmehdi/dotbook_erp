<?php

namespace App\Http\Controllers;

use App\Utils\OutstandingReceivableAndPayableUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutstandingReceivableController extends Controller
{
    public function __construct(private OutstandingReceivableAndPayableUtil $outstandingReceivableAndPayableUtil)
    {
    }

    public function index()
    {
        $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        $accountGroups = DB::table('account_groups')->whereIn('sub_sub_group_number', [6, 10])->where('is_reserved', 1)->select('name', 'sub_sub_group_number')->get();

        return view('finance.reports.outstanding_receivables.index', compact('users', 'accountGroups'));
    }

    public function outstandingReceivableDataView(Request $request)
    {
        $gs = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        if ($request->from_date && ! isset($request->to_date)) {

            return response()->json(['errorMsg' => 'To Date is required']);
        } elseif ($request->to_date && ! isset($request->from_date)) {

            return response()->json(['errorMsg' => 'From Date is required']);
        }

        $receivables = $this->outstandingReceivableAndPayableUtil->outstandingReceivableAndPayable($request, $accountStartDate);

        return view(
            'finance.reports.outstanding_receivables.ajax_view.outstanding_receivables_data_view',
            compact(
                'receivables',
                'accountStartDate',
                'fromDate',
                'toDate'
            )
        );
    }

    public function outstandingReceivableDataPrint(Request $request)
    {
        $gs = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $accountGroupHeadName = $request->account_group_head_name;

        if ($request->from_date && ! isset($request->to_date)) {

            return response()->json(['errorMsg' => 'To Date is required']);
        } elseif ($request->to_date && ! isset($request->from_date)) {

            return response()->json(['errorMsg' => 'From Date is required']);
        }

        $receivables = $this->outstandingReceivableAndPayableUtil->outstandingReceivableAndPayable($request, $accountStartDate);

        return view(
            'finance.reports.outstanding_receivables.ajax_view.outstanding_receivables_data_print',
            compact(
                'receivables',
                'accountStartDate',
                'fromDate',
                'toDate',
                'accountGroupHeadName',
            )
        );
    }
}
