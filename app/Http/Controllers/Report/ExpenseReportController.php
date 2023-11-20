<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExpenseReportController extends Controller
{
    public function __construct()
    {
    }

    // Index view of expense report
    public function index(Request $request)
    {
        if (! auth()->user()->can('expanse_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();

            $expenses = $this->expenseReportQuery($request)->orderBy('account_ledgers.date', 'desc');

            return DataTables::of($expenses)
                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })

                ->editColumn('voucher_type', function ($row) {

                    $accountLedgerUtil = new \App\Utils\AccountLedgerUtil;
                    $type = $accountLedgerUtil->voucherType($row->voucher_type);

                    return $row->voucher_type != 0 ? '<strong>'.$type['name'].'</strong>' : '';
                })

                ->editColumn('voucher_no', function ($row) {

                    $accountLedgerUtil = new \App\Utils\AccountLedgerUtil;
                    $type = $accountLedgerUtil->voucherType($row->voucher_type);

                    return '<a href="'.(! empty($type['link']) ? route($type['link'], $row->{$type['details_id']}) : '#').'" id="details_btn" class="fw-bold">'.$row->{$type['voucher_no']}.'</a>';
                })

                ->editColumn('amount', fn ($row) => '<span class="amount" data-value="'.$row->amount.'">'.\App\Utils\Converter::format_in_bdt($row->amount).'</span>')
                ->rawColumns(['date', 'voucher_type', 'voucher_no', 'amount'])
                ->make(true);
        }

        $expenseGroups = DB::table('account_groups')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select(
                'account_groups.id',
                'account_groups.name',
                'parentGroup.name as parent_group_name',
            )->get();

        $expenseAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name', 'account_groups.name as group_name')->get();

        return view('finance.reports.expense_report.index', compact('expenseAccounts', 'expenseGroups'));
    }

    public function print(Request $request)
    {
        $expenses = '';
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $expenseGroupName = $request->expense_group_name;
        $expenseAccountName = $request->expense_account_name;

        $expenses = $this->expenseReportQuery($request)->orderBy('accounts.id', 'asc')->orderBy('account_ledgers.date', 'desc')->get();

        $count = count($expenses);
        $veryLastAccountId = $count > 0 ? $expenses->last()->account_id : '';
        $lastRow = $count - 1;

        return view('finance.reports.expense_report.ajax_view.print', compact(
            'expenses',
            'fromDate',
            'toDate',
            'count',
            'veryLastAccountId',
            'lastRow',
            'expenseGroupName',
            'expenseAccountName',
        ));
    }

    private function expenseReportQuery($request)
    {
        $query = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('journal_entries', 'account_ledgers.journal_entry_id', 'journal_entries.id')
            ->leftJoin('journals', 'journal_entries.journal_id', 'journals.id')
            ->leftJoin('payment_descriptions', 'account_ledgers.payment_description_id', 'payment_descriptions.id')
            ->leftJoin('payments', 'payment_descriptions.payment_id', 'payments.id')
            ->leftJoin('expense_descriptions', 'account_ledgers.expense_description_id', 'expense_descriptions.id')
            ->leftJoin('expanses', 'expense_descriptions.expense_id', 'expanses.id')
            ->leftJoin('stock_adjustments', 'account_ledgers.adjustment_id', 'stock_adjustments.id')
            ->whereIn('account_groups.sub_group_number', [10, 11])->where('account_ledgers.debit', '>', 0);

        if ($request->expense_group_id) {

            $query->where('account_groups.id', $request->expense_group_id);
        }

        if ($request->expense_account_id) {

            $query->where('accounts.id', $request->expense_account_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('account_ledgers.date', $date_range); // Final
        }

        return $query->select(
            'accounts.id as account_id',
            'accounts.name as account_name',
            'account_groups.name as group_name',
            'account_ledgers.date',
            'account_ledgers.voucher_type',
            'journals.id as journal_id',
            'journals.voucher_no as journal_voucher',
            'payments.id as payment_id',
            'payments.voucher_no as payment_voucher',
            'expanses.id as expense_id',
            'expanses.voucher_no as expense_voucher',
            'stock_adjustments.voucher_no as stock_adjustment_voucher',
            'stock_adjustments.id as adjustment_id',
            'account_ledgers.debit as amount',
        );
    }
}
