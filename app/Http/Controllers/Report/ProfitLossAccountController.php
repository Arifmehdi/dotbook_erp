<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\ProfitLossAccountUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitLossAccountController extends Controller
{
    protected $profitLossAccountUtil;

    public function __construct(
        ProfitLossAccountUtil $profitLossAccountUtil
    ) {
        $this->profitLossAccountUtil = $profitLossAccountUtil;
    }

    public function index($fromDate = null, $toDate = null)
    {
        return view('finance.reports.profit_loss_account.index', compact('fromDate', 'toDate'));
    }

    public function profitLossAccountDataView(Request $request)
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

        $salesAccountBalance = $this->profitLossAccountUtil->salesAccountBalance($request, $accountStartDate);
        $purchaseAccountBalance = $this->profitLossAccountUtil->purchaseAccountBalance($request, $accountStartDate);
        $directExpenseAccountBalance = $this->profitLossAccountUtil->directExpenseAccountBalance($request, $accountStartDate);
        $directIncomesAccountBalance = $this->profitLossAccountUtil->directIncomesAccountBalance($request, $accountStartDate);
        $openingStock = $this->profitLossAccountUtil->openingStock();

        $closingStock = $this->profitLossAccountUtil->closingStock($request);
        $closingStock = $closingStock->closing_stock;

        $grossAmountOfDebit = 0;
        $grossAmountOfDebit += $salesAccountBalance['groupClosingBalanceSide'] == 'dr' ? $salesAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfDebit += $purchaseAccountBalance['groupClosingBalanceSide'] == 'dr' ? $purchaseAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfDebit += $directExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' ? $directExpenseAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfDebit += $directIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' ? $directIncomesAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfDebit += $openingStock;

        $grossAmountOfCredit = 0;
        $grossAmountOfCredit += $salesAccountBalance['groupClosingBalanceSide'] == 'cr' ? $salesAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfCredit += $purchaseAccountBalance['groupClosingBalanceSide'] == 'cr' ? $purchaseAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfCredit += $directExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' ? $directExpenseAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfCredit += $directIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' ? $directIncomesAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfCredit += $closingStock;

        $grossProfitOrLoss = 0;
        $grossProfitOrLossSide = 'dr';
        if ($grossAmountOfDebit > $grossAmountOfCredit) {

            $grossProfitOrLoss = $grossAmountOfDebit - $grossAmountOfCredit;
        } elseif ($grossAmountOfCredit > $grossAmountOfDebit) {

            $grossProfitOrLoss = $grossAmountOfCredit - $grossAmountOfDebit;
            $grossProfitOrLossSide = 'cr';
        }

        $indirectExpenseAccountBalance = $this->profitLossAccountUtil->indirectExpenseAccountBalance($request, $accountStartDate);
        $indirectIncomesAccountBalance = $this->profitLossAccountUtil->indirectIncomesAccountBalance($request, $accountStartDate);

        $netLoss = 0;
        $netProfit = 0;
        $netProfitLossSide = 'dr';
        if ($grossProfitOrLossSide == 'dr') {

            $netLoss += $grossProfitOrLoss;
            $netLoss += $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $netLoss += $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
            $netLoss -= $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $netLoss -= $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
        } elseif ($grossProfitOrLossSide == 'cr') {

            $netProfit += $grossProfitOrLoss;
            $netProfit += $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $netProfit += $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
            $netProfit -= $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $netProfit -= $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
            $netProfitLossSide = 'cr';
        }

        $totalNetAmountBothSide = 0;
        if ($grossProfitOrLossSide == 'dr') {

            $totalNetAmountBothSide += $grossProfitOrLoss;
            $totalNetAmountBothSide += $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $totalNetAmountBothSide += $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
        } elseif ($grossProfitOrLossSide == 'cr') {

            $totalNetAmountBothSide += $grossProfitOrLoss;
            $totalNetAmountBothSide += $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $totalNetAmountBothSide += $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
        }

        return view(
            'finance.reports.profit_loss_account.ajax_view.profit_loss_account_data_view',
            compact(
                'salesAccountBalance',
                'purchaseAccountBalance',
                'directExpenseAccountBalance',
                'directIncomesAccountBalance',
                'openingStock',
                'closingStock',
                'grossAmountOfDebit',
                'grossAmountOfCredit',
                'grossProfitOrLoss',
                'grossProfitOrLossSide',
                'indirectExpenseAccountBalance',
                'indirectIncomesAccountBalance',
                'netLoss',
                'netProfit',
                'netProfitLossSide',
                'totalNetAmountBothSide',
                'formatOfReport',
                'fromDate',
                'toDate',
            )
        );
    }

    public function profitLossAccountDataPrint(Request $request)
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

        $salesAccountBalance = $this->profitLossAccountUtil->salesAccountBalance($request, $accountStartDate);
        $purchaseAccountBalance = $this->profitLossAccountUtil->purchaseAccountBalance($request, $accountStartDate);
        $directExpenseAccountBalance = $this->profitLossAccountUtil->directExpenseAccountBalance($request, $accountStartDate);
        $directIncomesAccountBalance = $this->profitLossAccountUtil->directIncomesAccountBalance($request, $accountStartDate);
        $openingStock = $this->profitLossAccountUtil->openingStock();

        $closingStock = $this->profitLossAccountUtil->closingStock($request);
        $closingStock = $closingStock->closing_stock;

        $grossAmountOfDebit = 0;
        $grossAmountOfDebit += $salesAccountBalance['groupClosingBalanceSide'] == 'dr' ? $salesAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfDebit += $purchaseAccountBalance['groupClosingBalanceSide'] == 'dr' ? $purchaseAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfDebit += $directExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' ? $directExpenseAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfDebit += $directIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' ? $directIncomesAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfDebit += $openingStock;

        $grossAmountOfCredit = 0;
        $grossAmountOfCredit += $salesAccountBalance['groupClosingBalanceSide'] == 'cr' ? $salesAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfCredit += $purchaseAccountBalance['groupClosingBalanceSide'] == 'cr' ? $purchaseAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfCredit += $directExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' ? $directExpenseAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfCredit += $directIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' ? $directIncomesAccountBalance['groupClosingBalance'] : 0;
        $grossAmountOfCredit += $closingStock;

        $grossProfitOrLoss = 0;
        $grossProfitOrLossSide = 'dr';
        if ($grossAmountOfDebit > $grossAmountOfCredit) {

            $grossProfitOrLoss = $grossAmountOfDebit - $grossAmountOfCredit;
        } elseif ($grossAmountOfCredit > $grossAmountOfDebit) {

            $grossProfitOrLoss = $grossAmountOfCredit - $grossAmountOfDebit;
            $grossProfitOrLossSide = 'cr';
        }

        $indirectExpenseAccountBalance = $this->profitLossAccountUtil->indirectExpenseAccountBalance($request, $accountStartDate);
        $indirectIncomesAccountBalance = $this->profitLossAccountUtil->indirectIncomesAccountBalance($request, $accountStartDate);

        $netLoss = 0;
        $netProfit = 0;
        $netProfitLossSide = 'dr';
        if ($grossProfitOrLossSide == 'dr') {

            $netLoss += $grossProfitOrLoss;
            $netLoss += $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $netLoss += $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
            $netLoss -= $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $netLoss -= $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
        } elseif ($grossProfitOrLossSide == 'cr') {

            $netProfit += $grossProfitOrLoss;
            $netProfit += $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $netProfit += $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
            $netProfit -= $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $netProfit -= $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
            $netProfitLossSide = 'cr';
        }

        $totalNetAmountBothSide = 0;
        if ($grossProfitOrLossSide == 'dr') {

            $totalNetAmountBothSide += $grossProfitOrLoss;
            $totalNetAmountBothSide += $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $totalNetAmountBothSide += $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
        } elseif ($grossProfitOrLossSide == 'cr') {

            $totalNetAmountBothSide += $grossProfitOrLoss;
            $totalNetAmountBothSide += $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $totalNetAmountBothSide += $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
        }

        return view(
            'finance.reports.profit_loss_account.ajax_view.profit_loss_account_data_print',
            compact(
                'salesAccountBalance',
                'purchaseAccountBalance',
                'directExpenseAccountBalance',
                'directIncomesAccountBalance',
                'openingStock',
                'closingStock',
                'grossAmountOfDebit',
                'grossAmountOfCredit',
                'grossProfitOrLoss',
                'grossProfitOrLossSide',
                'indirectExpenseAccountBalance',
                'indirectIncomesAccountBalance',
                'netLoss',
                'netProfit',
                'netProfitLossSide',
                'totalNetAmountBothSide',
                'formatOfReport',
                'fromDate',
                'toDate',
            )
        );
    }
}
