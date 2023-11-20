<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\AccountGroupSummaryUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountGroupSummaryController extends Controller
{
    protected $accountGroupSummaryUtil;

    public function __construct(AccountGroupSummaryUtil $accountGroupSummaryUtil)
    {
        $this->accountGroupSummaryUtil = $accountGroupSummaryUtil;
    }

    public function index($groupId, $fromDate = null, $toDate = null)
    {
        $accountGroup = DB::table('account_groups')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->select('account_groups.*', 'parentGroup.name as parent_group_name')
            ->where('account_groups.id', $groupId)->first();

        return view('finance.reports.group_summary.index', compact('accountGroup', 'fromDate', 'toDate'));
    }

    public function groupSummaryView(Request $request, $groupId)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $gs = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

        $mainGroup = $this->accountGroupSummaryUtil->accountGroupSummaryViewDate($groupId, $fromDate, $toDate, $accountStartDate);

        return view('finance.reports.group_summary.ajax_view.group_summary_view', compact('mainGroup', 'fromDate', 'toDate'));
    }
}
