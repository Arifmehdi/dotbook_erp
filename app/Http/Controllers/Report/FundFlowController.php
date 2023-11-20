<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\FundFlowUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundFlowController extends Controller
{
    protected $fundFlowUtil;

    public function __construct(FundFlowUtil $fundFlowUtil)
    {
        $this->fundFlowUtil = $fundFlowUtil;
    }

    public function index()
    {
        return view('finance.reports.fund_flow.index');
    }

    public function fundFlowDataView(Request $request)
    {
        if ($request->from_date && ! isset($request->to_date)) {

            return response()->json(['errorMsg' => 'To Date is required']);
        } elseif ($request->to_date && ! isset($request->from_date)) {

            return response()->json(['errorMsg' => 'From Date is required']);
        }

        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $gs = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));
        $formatOfReport = $request->format_of_report;

        $capitalAccount = $this->fundFlowUtil->capitalAccountBalance($request, $accountStartDate);
        $loanLiabilitiesAccount = $this->fundFlowUtil->loanLiabilitiesAccountBalance($request, $accountStartDate);
        $branchAndDivisionsAccount = $this->fundFlowUtil->branchAndDivisionsAccountBalance($request, $accountStartDate);
        $suspenseAccount = $this->fundFlowUtil->suspenseAccountBalance($request, $accountStartDate);
        $netProfitLoss = $this->fundFlowUtil->netProfitLoss($request, $accountStartDate);

        $totalDebit = 0;
        $totalCredit = 0;
        $totalDebit += $capitalAccount->closing_balance_side == 'dr' ? $capitalAccount->closing_balance : 0;
        $totalDebit += $loanLiabilitiesAccount->closing_balance_side == 'dr' ? $loanLiabilitiesAccount->closing_balance : 0;
        $totalDebit += $branchAndDivisionsAccount->closing_balance_side == 'dr' ? $branchAndDivisionsAccount->closing_balance : 0;
        $totalDebit += $suspenseAccount->closing_balance_side == 'dr' ? $suspenseAccount->closing_balance : 0;

        $totalDebit += $netProfitLoss['netProfitLossSide'] == 'dr' ? $netProfitLoss['netLoss'] : 0;
        $totalDebit += $netProfitLoss['netProfitLossSide'] == 'cr' ? $netProfitLoss['netProfit'] : 0;

        $totalCredit += $capitalAccount->closing_balance_side == 'cr' ? $capitalAccount->closing_balance : 0;
        $totalCredit += $loanLiabilitiesAccount->closing_balance_side == 'cr' ? $loanLiabilitiesAccount->closing_balance : 0;
        $totalCredit += $branchAndDivisionsAccount->closing_balance_side == 'cr' ? $branchAndDivisionsAccount->closing_balance : 0;
        $totalCredit += $suspenseAccount->closing_balance_side == 'cr' ? $suspenseAccount->closing_balance : 0;

        $currentAssetAccount = $this->fundFlowUtil->currentAssetAccountBalance($request, $accountStartDate);
        $currentLiabilitiesAccount = $this->fundFlowUtil->currentLiabilitiesAccountBalance($request, $accountStartDate);

        return view(
            'finance.reports.fund_flow.ajax_view.fund_flow_data_view',
            compact(
                'capitalAccount',
                'loanLiabilitiesAccount',
                'branchAndDivisionsAccount',
                'suspenseAccount',
                'netProfitLoss',
                'totalDebit',
                'totalCredit',
                'currentAssetAccount',
                'currentLiabilitiesAccount',
                'formatOfReport',
                'fromDate',
                'toDate'
            )
        );
    }

    public function fundFlowDataPrint(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        if ($request->from_date && ! isset($request->to_date)) {

            return response()->json(['errorMsg' => 'To Date is required']);
        } elseif ($request->to_date && ! isset($request->from_date)) {

            return response()->json(['errorMsg' => 'From Date is required']);
        }

        $gs = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));
        $formatOfReport = $request->format_of_report;

        $capitalAccount = $this->fundFlowUtil->capitalAccountBalance($request, $accountStartDate);
        $loanLiabilitiesAccount = $this->fundFlowUtil->loanLiabilitiesAccountBalance($request, $accountStartDate);
        $branchAndDivisionsAccount = $this->fundFlowUtil->branchAndDivisionsAccountBalance($request, $accountStartDate);
        $suspenseAccount = $this->fundFlowUtil->suspenseAccountBalance($request, $accountStartDate);
        $netProfitLoss = $this->fundFlowUtil->netProfitLoss($request, $accountStartDate);

        $totalDebit = 0;
        $totalCredit = 0;
        $totalDebit += $capitalAccount->closing_balance_side == 'dr' ? $capitalAccount->closing_balance : 0;
        $totalDebit += $loanLiabilitiesAccount->closing_balance_side == 'dr' ? $loanLiabilitiesAccount->closing_balance : 0;
        $totalDebit += $branchAndDivisionsAccount->closing_balance_side == 'dr' ? $branchAndDivisionsAccount->closing_balance : 0;
        $totalDebit += $suspenseAccount->closing_balance_side == 'dr' ? $suspenseAccount->closing_balance : 0;

        $totalDebit += $netProfitLoss['netProfitLossSide'] == 'dr' ? $netProfitLoss['netLoss'] : 0;
        $totalDebit += $netProfitLoss['netProfitLossSide'] == 'cr' ? $netProfitLoss['netProfit'] : 0;

        $totalCredit += $capitalAccount->closing_balance_side == 'cr' ? $capitalAccount->closing_balance : 0;
        $totalCredit += $loanLiabilitiesAccount->closing_balance_side == 'cr' ? $loanLiabilitiesAccount->closing_balance : 0;
        $totalCredit += $branchAndDivisionsAccount->closing_balance_side == 'cr' ? $branchAndDivisionsAccount->closing_balance : 0;
        $totalCredit += $suspenseAccount->closing_balance_side == 'cr' ? $suspenseAccount->closing_balance : 0;

        $currentAssetAccount = $this->fundFlowUtil->currentAssetAccountBalance($request, $accountStartDate);
        $currentLiabilitiesAccount = $this->fundFlowUtil->currentLiabilitiesAccountBalance($request, $accountStartDate);

        return view(
            'finance.reports.fund_flow.ajax_view.fund_flow_data_print',
            compact(
                'capitalAccount',
                'loanLiabilitiesAccount',
                'branchAndDivisionsAccount',
                'suspenseAccount',
                'netProfitLoss',
                'totalDebit',
                'totalCredit',
                'currentAssetAccount',
                'currentLiabilitiesAccount',
                'formatOfReport',
                'fromDate',
                'toDate',
            )
        );
    }
}
