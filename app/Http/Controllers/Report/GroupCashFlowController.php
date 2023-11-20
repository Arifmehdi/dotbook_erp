<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\GroupCashFlowUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupCashFlowController extends Controller
{
    public function __construct(private GroupCashFlowUtil $groupCashFlowUtil)
    {
    }

    public function index($groupId, $cashFlowSide, $fromDate = null, $toDate = null)
    {
        $accountGroup = DB::table('account_groups')->where('id', $groupId)->first();

        return view('finance.reports.cash_flow.group_cash_flow.index', compact('accountGroup', 'cashFlowSide', 'fromDate', 'toDate'));
    }

    public function groupCashFlowView(Request $request, $groupId, $cashFlowSide)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $groupCashflow = $this->groupCashFlowUtil->groupCashFlowDetails($groupId, $fromDate, $toDate);

        return view('finance.reports.cash_flow.group_cash_flow.ajax_view.group_cash_flow_view', compact('groupCashflow', 'cashFlowSide', 'fromDate', 'toDate'));
    }

    public function groupCashFlowPrint(Request $request, $groupId, $cashFlowSide)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $groupCashflow = $this->groupCashFlowUtil->groupCashFlowDetails($groupId, $fromDate, $toDate);

        return view('finance.reports.cash_flow.group_cash_flow.ajax_view.group_cash_flow_print', compact('groupCashflow', 'cashFlowSide', 'fromDate', 'toDate'));
    }
}
