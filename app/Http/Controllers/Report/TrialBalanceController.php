<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\TrialBalanceUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrialBalanceController extends Controller
{
    public function __construct(private TrialBalanceUtil $trialBalanceUtil)
    {
    }

    public function index()
    {
        return view('finance.reports.trial_balance.index');
    }

    public function trialBalanceDataView(Request $request)
    {
        if ($request->from_date && ! isset($request->to_date)) {

            return response()->json(['errorMsg' => 'To Date is required']);
        } elseif ($request->to_date && ! isset($request->from_date)) {

            return response()->json(['errorMsg' => 'From Date is required']);
        }

        $gs = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $formatOfReport = $request->format_of_report;

        $profitLossAccountUtil = new \App\Utils\ProfitLossAccountUtil();
        $openingStock = $profitLossAccountUtil->openingStock();

        if ($request->showing_type == 'group_wise') {

            $accountGroups = $this->trialBalanceUtil->balanceGroupWise($request, $accountStartDate);

            return view('finance.reports.trial_balance.ajax_view.trial_balance_group_wise_view', compact('accountGroups', 'openingStock', 'fromDate', 'toDate', 'formatOfReport'));
        } else {

            $accounts = $this->queryByAccount($request, $accountStartDate);

            return view('finance.reports.trial_balance.ajax_view.trial_balance_account_wise', compact('accounts', 'openingStock', 'fromDate', 'toDate'));
        }
    }

    public function print(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $formatOfReport = $request->format_of_report;

        $gs = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

        $profitLossAccountUtil = new \App\Utils\ProfitLossAccountUtil();
        $openingStock = $profitLossAccountUtil->openingStock();

        if ($request->from_date && ! isset($request->to_date)) {

            return response()->json(['errorMsg' => 'To Date is required']);
        } elseif ($request->to_date && ! isset($request->from_date)) {

            return response()->json(['errorMsg' => 'From Date is required']);
        }

        if ($request->showing_type == 'group_wise') {

            $accountGroups = $this->trialBalanceUtil->balanceGroupWise($request, $accountStartDate);

            return view(
                'finance.reports.trial_balance.ajax_view.trial_balance_group_wise_print',
                compact(
                    'accountGroups',
                    'openingStock',
                    'fromDate',
                    'toDate',
                    'formatOfReport'
                )
            );
        } else {

            $accounts = $this->queryByAccount($request, $accountStartDate);

            return view(
                'finance.reports.trial_balance.ajax_view.trial_balance_account_wise_print',
                compact(
                    'accounts',
                    'openingStock',
                    'fromDate',
                    'toDate'
                )
            );
        }
    }

    public function queryByAccount($request, $accountStartDate)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        $accountStartDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $accounts = '';
        $query = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                'accounts.id as account_id',
                'accounts.name as account_name',
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'parentGroup.name as parent_group_name',

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } elseif ($fromDateYmd && $toDateYmd && $fromDateYmd <= $accountStartDateYmd) {

            $query->select(
                'accounts.id as account_id',
                'accounts.name as account_name',
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'parentGroup.name as parent_group_name',

                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type = 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type = 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } else {

            $query->select(
                'accounts.id as account_id',
                'accounts.name as account_name',
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'parentGroup.name as parent_group_name',

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.debit end), 0) as opening_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.credit end), 0) as opening_total_credit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.debit end), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.credit end), 0) as curr_total_credit'),
            );
        }

        $accounts = $query
            ->groupBy('accounts.id')
            ->groupBy('accounts.name')
            ->groupBy('account_groups.id')
            ->groupBy('account_groups.name')
            ->groupBy('parentGroup.name')
            // ->orderBy('account_groups.id', 'asc')
            // ->orderBy('parentGroup.name', 'asc')
            // ->orderBy('parentGroup.id', 'asc')
            // ->orderBy('account_groups.sub_sub_group_name', 'asc')
            ->orderBy('account_groups.sub_group_name', 'asc')
            ->orderBy('account_groups.id', 'asc')

            ->get();

        return $accounts;
    }
}
