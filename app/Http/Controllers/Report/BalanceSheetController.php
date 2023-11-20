<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\BalanceSheetUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceSheetController extends Controller
{
    protected $balanceSheetUtil;

    public function __construct(BalanceSheetUtil $balanceSheetUtil)
    {
        $this->balanceSheetUtil = $balanceSheetUtil;
    }

    public function index()
    {
        return view('finance.reports.balance_sheet.index');
    }

    public function balanceSheetDataView(Request $request)
    {
        if ($request->from_date && ! isset($request->to_date)) {

            return response()->json(['errorMsg' => 'To Date is required']);
        } elseif ($request->to_date && ! isset($request->from_date)) {

            return response()->json(['errorMsg' => 'From Date is required']);
        }

        $gs = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));
        $formatOfReport = $request->format_of_report;
        $showingMethod = $request->showing_method;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $branchAndDivisionsAccountBalance = $this->balanceSheetUtil->branchAndDivisionsAccountBalance($request, $accountStartDate);
        $capitalAccount = $this->balanceSheetUtil->capitalAccountBalance($request, $accountStartDate);
        $suspenseAccountBalance = $this->balanceSheetUtil->suspenseAccountBalance($request, $accountStartDate);
        $loanLiabilitiesAccount = $this->balanceSheetUtil->loanLiabilitiesAccountBalance($request, $accountStartDate);
        $currentLiabilitiesAccount = $this->balanceSheetUtil->currentLiabilitiesAccountBalance($request, $accountStartDate);
        $fixedAssetsAccount = $this->balanceSheetUtil->fixedAssetAccountBalance($request, $accountStartDate);
        $investmentsAccount = $this->balanceSheetUtil->investmentsAccountBalance($request, $accountStartDate);
        $currentAssetAccount = $this->balanceSheetUtil->currentAssetAccountBalance($request, $accountStartDate);
        $profitLossAccount = $this->balanceSheetUtil->profitLossAccount($request, $accountStartDate);

        $totalDebit = 0;
        $totalCredit = 0;
        $totalDebit += $capitalAccount->closing_balance_side == 'dr' ? $capitalAccount->closing_balance : 0;
        $totalDebit += $branchAndDivisionsAccountBalance->closing_balance_side == 'dr' ? $branchAndDivisionsAccountBalance->closing_balance : 0;
        $totalDebit += $suspenseAccountBalance->closing_balance_side == 'dr' ? $suspenseAccountBalance->closing_balance : 0;
        $totalDebit += $loanLiabilitiesAccount->closing_balance_side == 'dr' ? $loanLiabilitiesAccount->closing_balance : 0;
        $totalDebit += $currentLiabilitiesAccount->closing_balance_side == 'dr' ? $currentLiabilitiesAccount->closing_balance : 0;
        $totalDebit += $fixedAssetsAccount->closing_balance_side == 'dr' ? $fixedAssetsAccount->closing_balance : 0;
        $totalDebit += $investmentsAccount->closing_balance_side == 'dr' ? $investmentsAccount->closing_balance : 0;
        $totalDebit += $currentAssetAccount['currentAssetsAccounts']->closing_balance_side == 'dr' ? $currentAssetAccount['currentAssetsAccounts']->closing_balance : 0;
        $totalDebit += $profitLossAccount['net_profit_loss_side'] == 'dr' ? $profitLossAccount['net_profit_loss'] : 0;

        $totalCredit += $branchAndDivisionsAccountBalance->closing_balance_side == 'cr' ? $branchAndDivisionsAccountBalance->closing_balance : 0;
        $totalCredit += $capitalAccount->closing_balance_side == 'cr' ? $capitalAccount->closing_balance : 0;
        $totalCredit += $suspenseAccountBalance->closing_balance_side == 'cr' ? $suspenseAccountBalance->closing_balance : 0;
        $totalCredit += $loanLiabilitiesAccount->closing_balance_side == 'cr' ? $loanLiabilitiesAccount->closing_balance : 0;
        $totalCredit += $currentLiabilitiesAccount->closing_balance_side == 'cr' ? $currentLiabilitiesAccount->closing_balance : 0;
        $totalCredit += $fixedAssetsAccount->closing_balance_side == 'cr' ? $fixedAssetsAccount->closing_balance : 0;
        $totalCredit += $investmentsAccount->closing_balance_side == 'cr' ? $investmentsAccount->closing_balance : 0;
        $totalCredit += $currentAssetAccount['currentAssetsAccounts']->closing_balance_side == 'cr' ? $currentAssetAccount['currentAssetsAccounts']->closing_balance : 0;
        $totalCredit += $profitLossAccount['net_profit_loss_side'] == 'cr' ? $profitLossAccount['net_profit_loss'] : 0;

        $differenceInOpeningBalance = 0;
        $differencesInOpeningBalanceSide = 'cr';
        if ($totalDebit > $totalCredit) {

            $differenceInOpeningBalance = $totalDebit - $totalCredit;
            $differencesInOpeningBalanceSide = 'dr';
        } elseif ($totalCredit > $totalDebit) {

            $differenceInOpeningBalance = $totalCredit - $totalDebit;
            $differencesInOpeningBalanceSide = 'cr';
        }

        $totalDebit += $differencesInOpeningBalanceSide == 'cr' ? $differenceInOpeningBalance : 0;
        $totalCredit += $differencesInOpeningBalanceSide == 'dr' ? $differenceInOpeningBalance : 0;

        $compact = compact(
            'branchAndDivisionsAccountBalance',
            'capitalAccount',
            'suspenseAccountBalance',
            'loanLiabilitiesAccount',
            'currentLiabilitiesAccount',
            'fixedAssetsAccount',
            'investmentsAccount',
            'currentAssetAccount',
            'profitLossAccount',
            'totalDebit',
            'totalCredit',
            'differenceInOpeningBalance',
            'differencesInOpeningBalanceSide',
            'formatOfReport',
            'fromDate',
            'toDate'
        );

        if ($showingMethod == 'liabilities-assets') {

            return view(
                'finance.reports.balance_sheet.ajax_view.balance_sheet_data_view_la_mode',
                $compact
            );
        } elseif ($showingMethod == 'assets-liabilities') {

            return view(
                'finance.reports.balance_sheet.ajax_view.balance_sheet_data_view_al_mode',
                $compact
            );
        }
    }

    public function balanceSheetDataPrint(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $formatOfReport = $request->format_of_report;

        if ($request->from_date && ! isset($request->to_date)) {

            return response()->json(['errorMsg' => 'To Date is required']);
        } elseif ($request->to_date && ! isset($request->from_date)) {

            return response()->json(['errorMsg' => 'From Date is required']);
        }

        $gs = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));
        $formatOfReport = $request->format_of_report;
        $showingMethod = $request->showing_method;

        $branchAndDivisionsAccountBalance = $this->balanceSheetUtil->branchAndDivisionsAccountBalance($request, $accountStartDate);
        $capitalAccount = $this->balanceSheetUtil->capitalAccountBalance($request, $accountStartDate);
        $suspenseAccountBalance = $this->balanceSheetUtil->suspenseAccountBalance($request, $accountStartDate);
        $loanLiabilitiesAccount = $this->balanceSheetUtil->loanLiabilitiesAccountBalance($request, $accountStartDate);
        $currentLiabilitiesAccount = $this->balanceSheetUtil->currentLiabilitiesAccountBalance($request, $accountStartDate);
        $fixedAssetsAccount = $this->balanceSheetUtil->fixedAssetAccountBalance($request, $accountStartDate);
        $investmentsAccount = $this->balanceSheetUtil->investmentsAccountBalance($request, $accountStartDate);
        $currentAssetAccount = $this->balanceSheetUtil->currentAssetAccountBalance($request, $accountStartDate);
        $profitLossAccount = $this->balanceSheetUtil->profitLossAccount($request, $accountStartDate);

        $totalDebit = 0;
        $totalCredit = 0;
        $totalDebit += $branchAndDivisionsAccountBalance->closing_balance_side == 'dr' ? $branchAndDivisionsAccountBalance->closing_balance : 0;
        $totalDebit += $capitalAccount->closing_balance_side == 'dr' ? $capitalAccount->closing_balance : 0;
        $totalDebit += $suspenseAccountBalance->closing_balance_side == 'dr' ? $suspenseAccountBalance->closing_balance : 0;
        $totalDebit += $loanLiabilitiesAccount->closing_balance_side == 'dr' ? $loanLiabilitiesAccount->closing_balance : 0;
        $totalDebit += $currentLiabilitiesAccount->closing_balance_side == 'dr' ? $currentLiabilitiesAccount->closing_balance : 0;
        $totalDebit += $fixedAssetsAccount->closing_balance_side == 'dr' ? $fixedAssetsAccount->closing_balance : 0;
        $totalDebit += $investmentsAccount->closing_balance_side == 'dr' ? $investmentsAccount->closing_balance : 0;
        $totalDebit += $currentAssetAccount['currentAssetsAccounts']->closing_balance_side == 'dr' ? $currentAssetAccount['currentAssetsAccounts']->closing_balance : 0;
        $totalDebit += $profitLossAccount['net_profit_loss_side'] == 'dr' ? $profitLossAccount['net_profit_loss'] : 0;

        $totalCredit += $branchAndDivisionsAccountBalance->closing_balance_side == 'cr' ? $branchAndDivisionsAccountBalance->closing_balance : 0;
        $totalCredit += $capitalAccount->closing_balance_side == 'cr' ? $capitalAccount->closing_balance : 0;
        $totalCredit += $suspenseAccountBalance->closing_balance_side == 'cr' ? $suspenseAccountBalance->closing_balance : 0;
        $totalCredit += $loanLiabilitiesAccount->closing_balance_side == 'cr' ? $loanLiabilitiesAccount->closing_balance : 0;
        $totalCredit += $currentLiabilitiesAccount->closing_balance_side == 'cr' ? $currentLiabilitiesAccount->closing_balance : 0;
        $totalCredit += $fixedAssetsAccount->closing_balance_side == 'cr' ? $fixedAssetsAccount->closing_balance : 0;
        $totalCredit += $investmentsAccount->closing_balance_side == 'cr' ? $investmentsAccount->closing_balance : 0;
        $totalCredit += $currentAssetAccount['currentAssetsAccounts']->closing_balance_side == 'cr' ? $currentAssetAccount['currentAssetsAccounts']->closing_balance : 0;
        $totalCredit += $profitLossAccount['net_profit_loss_side'] == 'cr' ? $profitLossAccount['net_profit_loss'] : 0;

        $differenceInOpeningBalance = 0;
        $differencesInOpeningBalanceSide = 'cr';
        if ($totalDebit > $totalCredit) {

            $differenceInOpeningBalance = $totalDebit - $totalCredit;
            $differencesInOpeningBalanceSide = 'dr';
        } elseif ($totalCredit > $totalDebit) {

            $differenceInOpeningBalance = $totalCredit - $totalDebit;
            $differencesInOpeningBalanceSide = 'cr';
        }

        $totalDebit += $differencesInOpeningBalanceSide == 'cr' ? $differenceInOpeningBalance : 0;
        $totalCredit += $differencesInOpeningBalanceSide == 'dr' ? $differenceInOpeningBalance : 0;

        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $formatOfReport = $request->format_of_report;

        $compact = compact(
            'branchAndDivisionsAccountBalance',
            'capitalAccount',
            'suspenseAccountBalance',
            'loanLiabilitiesAccount',
            'currentLiabilitiesAccount',
            'fixedAssetsAccount',
            'investmentsAccount',
            'currentAssetAccount',
            'profitLossAccount',
            'totalDebit',
            'totalCredit',
            'differenceInOpeningBalance',
            'differencesInOpeningBalanceSide',
            'formatOfReport',
            'fromDate',
            'toDate',
        );

        if ($showingMethod == 'liabilities-assets') {

            return view(
                'finance.reports.balance_sheet.ajax_view.balance_sheet_data_print_la_mode',
                $compact
            );
        } elseif ($showingMethod == 'assets-liabilities') {

            return view(
                'finance.reports.balance_sheet.ajax_view.balance_sheet_data_print_al_mode',
                $compact
            );
        }

    }
}
