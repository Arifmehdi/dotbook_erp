<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\CashFlowUtil;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    protected $cashFlowUtil;

    public function __construct(CashFlowUtil $cashFlowUtil)
    {
        $this->cashFlowUtil = $cashFlowUtil;
    }

    public function index()
    {
        return view('finance.reports.cash_flow.index');
    }

    public function cashFlowView(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        if ($request->from_date && ! isset($request->to_date)) {

            return response()->json(['errorMsg' => 'To Date is required']);
        } elseif ($request->to_date && ! isset($request->from_date)) {

            return response()->json(['errorMsg' => 'From Date is required']);
        }

        $formatOfReport = $request->format_of_report;
        $capitalAccountCashFlows = $this->cashFlowUtil->capitalAccountCashFlows($request);
        $branchAndDivisionCashFlows = $this->cashFlowUtil->branchAndDivisionCashFlows($request);
        $suspenseAccountCashFlows = $this->cashFlowUtil->suspenseAccountCashFlows($request);
        $currentLiabilitiesCashFlows = $this->cashFlowUtil->currentLiabilitiesCashFlows($request);
        $loanLiabilitiesCashFlows = $this->cashFlowUtil->loanLiabilitiesCashFlows($request);
        $currentAssetsCashFlows = $this->cashFlowUtil->currentAssetsCashFlows($request);
        $fixedAssetsCashFlows = $this->cashFlowUtil->fixedAssetsCashFlows($request);
        $investmentsCashFlows = $this->cashFlowUtil->investmentsCashFlows($request);
        $directExpenseCashFlows = $this->cashFlowUtil->directExpenseCashFlows($request);
        $indirectExpenseCashFlows = $this->cashFlowUtil->indirectExpenseCashFlows($request);
        $purchaseCashFlows = $this->cashFlowUtil->purchaseCashFlows($request);
        $directIncomeCashFlows = $this->cashFlowUtil->directIncomeCashFlows($request);
        $indirectIncomeCashFlows = $this->cashFlowUtil->indirectIncomeCashFlows($request);
        $salesAccountCashFlows = $this->cashFlowUtil->salesAccountCashFlows($request);

        $totalIn = 0;
        $totalOut = 0;

        $totalIn += $capitalAccountCashFlows->cash_in;
        $totalIn += $branchAndDivisionCashFlows->cash_in;
        $totalIn += $suspenseAccountCashFlows->cash_in;
        $totalIn += $currentLiabilitiesCashFlows->cash_in;
        $totalIn += $currentAssetsCashFlows->cash_in;
        $totalIn += $fixedAssetsCashFlows->cash_in;
        $totalIn += $investmentsCashFlows->cash_in;
        $totalIn += $directExpenseCashFlows->cash_in;
        $totalIn += $indirectExpenseCashFlows->cash_in;
        $totalIn += $purchaseCashFlows->cash_in;
        $totalIn += $directIncomeCashFlows->cash_in;
        $totalIn += $indirectIncomeCashFlows->cash_in;
        $totalIn += $salesAccountCashFlows->cash_in;

        $totalOut += $capitalAccountCashFlows->cash_out;
        $totalOut += $branchAndDivisionCashFlows->cash_out;
        $totalOut += $suspenseAccountCashFlows->cash_out;
        $totalOut += $currentLiabilitiesCashFlows->cash_out;
        $totalOut += $currentAssetsCashFlows->cash_out;
        $totalOut += $fixedAssetsCashFlows->cash_out;
        $totalOut += $investmentsCashFlows->cash_out;
        $totalOut += $directExpenseCashFlows->cash_out;
        $totalOut += $indirectExpenseCashFlows->cash_out;
        $totalOut += $purchaseCashFlows->cash_out;
        $totalOut += $directIncomeCashFlows->cash_out;
        $totalOut += $indirectIncomeCashFlows->cash_out;
        $totalOut += $salesAccountCashFlows->cash_out;

        $balance = 0;
        $balanceSide = 'in';

        if ($totalIn > $totalOut) {

            $balance = $totalIn - $totalOut;
            $balanceSide = 'in';
        } elseif ($totalOut > $totalIn) {

            $balance = $totalOut - $totalIn;
            $balanceSide = 'out';
        }

        return view(
            'finance.reports.cash_flow.ajax_view.cash_flow_data_view',
            compact(
                'capitalAccountCashFlows',
                'branchAndDivisionCashFlows',
                'suspenseAccountCashFlows',
                'currentLiabilitiesCashFlows',
                'loanLiabilitiesCashFlows',
                'currentAssetsCashFlows',
                'fixedAssetsCashFlows',
                'investmentsCashFlows',
                'directExpenseCashFlows',
                'indirectExpenseCashFlows',
                'purchaseCashFlows',
                'directIncomeCashFlows',
                'indirectIncomeCashFlows',
                'salesAccountCashFlows',
                'totalIn',
                'totalOut',
                'balance',
                'balanceSide',
                'formatOfReport',
                'fromDate',
                'toDate',
            )
        );
    }

    public function cashFlowPrint(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $formatOfReport = $request->format_of_report;

        if ($request->from_date && ! isset($request->to_date)) {

            return response()->json(['errorMsg' => 'To Date is required']);
        } elseif ($request->to_date && ! isset($request->from_date)) {

            return response()->json(['errorMsg' => 'From Date is required']);
        }

        $formatOfReport = $request->format_of_report;
        $capitalAccountCashFlows = $this->cashFlowUtil->capitalAccountCashFlows($request);
        $branchAndDivisionCashFlows = $this->cashFlowUtil->branchAndDivisionCashFlows($request);
        $suspenseAccountCashFlows = $this->cashFlowUtil->suspenseAccountCashFlows($request);
        $currentLiabilitiesCashFlows = $this->cashFlowUtil->currentLiabilitiesCashFlows($request);
        $loanLiabilitiesCashFlows = $this->cashFlowUtil->loanLiabilitiesCashFlows($request);
        $currentAssetsCashFlows = $this->cashFlowUtil->currentAssetsCashFlows($request);
        $fixedAssetsCashFlows = $this->cashFlowUtil->fixedAssetsCashFlows($request);
        $investmentsCashFlows = $this->cashFlowUtil->investmentsCashFlows($request);
        $directExpenseCashFlows = $this->cashFlowUtil->directExpenseCashFlows($request);
        $indirectExpenseCashFlows = $this->cashFlowUtil->indirectExpenseCashFlows($request);
        $purchaseCashFlows = $this->cashFlowUtil->purchaseCashFlows($request);
        $directIncomeCashFlows = $this->cashFlowUtil->directIncomeCashFlows($request);
        $indirectIncomeCashFlows = $this->cashFlowUtil->indirectIncomeCashFlows($request);
        $salesAccountCashFlows = $this->cashFlowUtil->salesAccountCashFlows($request);

        $totalIn = 0;
        $totalOut = 0;

        $totalIn += $capitalAccountCashFlows->cash_in;
        $totalIn += $branchAndDivisionCashFlows->cash_in;
        $totalIn += $suspenseAccountCashFlows->cash_in;
        $totalIn += $currentLiabilitiesCashFlows->cash_in;
        $totalIn += $currentAssetsCashFlows->cash_in;
        $totalIn += $fixedAssetsCashFlows->cash_in;
        $totalIn += $investmentsCashFlows->cash_in;
        $totalIn += $directExpenseCashFlows->cash_in;
        $totalIn += $indirectExpenseCashFlows->cash_in;
        $totalIn += $purchaseCashFlows->cash_in;
        $totalIn += $directIncomeCashFlows->cash_in;
        $totalIn += $indirectIncomeCashFlows->cash_in;
        $totalIn += $salesAccountCashFlows->cash_in;

        $totalOut += $capitalAccountCashFlows->cash_out;
        $totalOut += $branchAndDivisionCashFlows->cash_out;
        $totalOut += $suspenseAccountCashFlows->cash_out;
        $totalOut += $currentLiabilitiesCashFlows->cash_out;
        $totalOut += $currentAssetsCashFlows->cash_out;
        $totalOut += $fixedAssetsCashFlows->cash_out;
        $totalOut += $investmentsCashFlows->cash_out;
        $totalOut += $directExpenseCashFlows->cash_out;
        $totalOut += $indirectExpenseCashFlows->cash_out;
        $totalOut += $purchaseCashFlows->cash_out;
        $totalOut += $directIncomeCashFlows->cash_out;
        $totalOut += $indirectIncomeCashFlows->cash_out;
        $totalOut += $salesAccountCashFlows->cash_out;

        $balance = 0;
        $balanceSide = 'in';

        if ($totalIn > $totalOut) {

            $balance = $totalIn - $totalOut;
            $balanceSide = 'in';
        } elseif ($totalOut > $totalIn) {

            $balance = $totalOut - $totalIn;
            $balanceSide = 'out';
        }

        return view(
            'finance.reports.cash_flow.ajax_view.cash_flow_data_print',
            compact(
                'capitalAccountCashFlows',
                'branchAndDivisionCashFlows',
                'suspenseAccountCashFlows',
                'currentLiabilitiesCashFlows',
                'loanLiabilitiesCashFlows',
                'currentAssetsCashFlows',
                'fixedAssetsCashFlows',
                'investmentsCashFlows',
                'directExpenseCashFlows',
                'indirectExpenseCashFlows',
                'purchaseCashFlows',
                'directIncomeCashFlows',
                'indirectIncomeCashFlows',
                'salesAccountCashFlows',
                'totalIn',
                'totalOut',
                'balance',
                'balanceSide',
                'formatOfReport',
                'fromDate',
                'toDate',
            )
        );
    }
}
