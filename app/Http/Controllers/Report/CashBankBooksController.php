<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\CashBankBooksUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashBankBooksController extends Controller
{
    public function __construct(private CashBankBooksUtil $cashBankBooksUtil)
    {
    }

    public function index()
    {

        return view('finance.reports.cash_bank_books.index');
    }

    public function cashBankBooksView(Request $request)
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

        if ($request->showing_type == 'group_wise') {

            $mainGroups = $this->cashBankBooksUtil->cashBankBooksGroupWise($fromDate, $toDate, $accountStartDate);

            return view('finance.reports.cash_bank_books.ajax_view.group_wise_view', compact('mainGroups', 'fromDate', 'toDate'));
        } else {

            $accounts = $this->cashBankBooksUtil->cashBankBooksAccountWise($fromDate, $toDate, $accountStartDate);

            return view('finance.reports.cash_bank_books.ajax_view.ledger_wise_view', compact('accounts', 'fromDate', 'toDate'));
        }
    }

    public function cashBankBooksPrint(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $formatOfReport = $request->format_of_report;

        $gs = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

        if ($request->from_date && ! isset($request->to_date)) {

            return response()->json(['errorMsg' => 'To Date is required']);
        } elseif ($request->to_date && ! isset($request->from_date)) {

            return response()->json(['errorMsg' => 'From Date is required']);
        }

        if ($request->showing_type == 'group_wise') {

            $mainGroups = $this->cashBankBooksUtil->cashBankBooksGroupWise($fromDate, $toDate, $accountStartDate);

            return view(
                'finance.reports.cash_bank_books.ajax_view.group_wise_print',
                compact(
                    'mainGroups',
                    'fromDate',
                    'toDate',
                )
            );
        } else {

            $accounts = $this->cashBankBooksUtil->cashBankBooksAccountWise($fromDate, $toDate, $accountStartDate);

            return view(
                'finance.reports.cash_bank_books.ajax_view.ledger_wise_print',
                compact(
                    'accounts',
                    'fromDate',
                    'toDate'
                )
            );
        }
    }
}
