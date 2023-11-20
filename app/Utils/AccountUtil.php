<?php

namespace App\Utils;

use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AccountUtil
{
    public function accountListTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $accounts = '';
        $query = DB::table('accounts')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if ($request->account_group_id) {

            $query->where('accounts.account_group_id', $request->account_group_id);
        }

        $accounts = $query->select(
            'accounts.id',
            'accounts.name',
            'accounts.account_number',
            'accounts.opening_balance',
            'accounts.balance',
            'banks.name as b_name',
            'account_groups.name as group_name',
            DB::raw(
                '
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 0
                            THEN account_ledgers.debit
                            ELSE 0
                        END
                    ) AS opening_total_debit,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 0
                            THEN account_ledgers.credit
                            ELSE 0
                        END
                    ) AS opening_total_credit,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type != 0
                            THEN account_ledgers.debit
                            ELSE 0
                        END
                    ) AS curr_total_debit,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type != 0
                            THEN account_ledgers.credit
                            ELSE 0
                        END
                    ) AS curr_total_credit
                '
            ),
        )->groupBy(
            'accounts.id',
            'accounts.name',
            'accounts.account_number',
            'accounts.opening_balance',
            'accounts.balance',
            'banks.name',
            'account_groups.name',
        )->orderBy('parentGroup.id', 'asc')
            ->orderBy('account_groups.id', 'asc')
            ->orderBy('accounts.name', 'asc');

        return DataTables::of($accounts)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                if (auth()->user()->can('accounts_edit')) {

                    $html .= '<a id="edit" class="dropdown-item" href="'.route('accounting.accounts.edit', [$row->id]).'" >'.__('menu.edit').'</a>';
                }

                if (auth()->user()->can('accounts_ledger')) {

                    $html .= '<a class="dropdown-item" href="'.route('accounting.accounts.ledger', [$row->id, 'accountId']).'">'.__('menu.ledger').'</a>';
                }

                if (auth()->user()->can('accounts_delete')) {

                    $html .= '<a class="dropdown-item" href="'.route('accounting.accounts.delete', [$row->id]).'" id="delete">'.__('menu.delete').'</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('opening_balance', function ($row) {

                $openingBalanceDebit = isset($row->opening_total_debit) ? (float) $row->opening_total_debit : 0;
                $openingBalanceCredit = isset($row->opening_total_credit) ? (float) $row->opening_total_credit : 0;

                $currOpeningBalance = 0;
                $currOpeningBalanceSide = 'dr';

                if ($openingBalanceDebit > $openingBalanceCredit) {

                    $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                    $currOpeningBalanceSide = 'dr';
                } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                    $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                    $currOpeningBalanceSide = 'cr';
                }

                return \App\Utils\Converter::format_in_bdt($currOpeningBalance).' '.ucfirst($currOpeningBalanceSide).'.';
            })
            ->editColumn('debit', function ($row) {

                return \App\Utils\Converter::format_in_bdt($row->curr_total_debit);
            })
            ->editColumn('credit', function ($row) {

                return \App\Utils\Converter::format_in_bdt($row->curr_total_credit);
            })
            ->editColumn('closing_balance', function ($row) {

                $openingBalanceDebit = isset($row->opening_total_debit) ? (float) $row->opening_total_debit : 0;
                $openingBalanceCredit = isset($row->opening_total_credit) ? (float) $row->opening_total_credit : 0;

                $CurrTotalDebit = (float) $row->curr_total_debit;
                $CurrTotalCredit = (float) $row->curr_total_credit;

                $currOpeningBalance = 0;
                $currOpeningBalanceSide = 'dr';

                if ($openingBalanceDebit > $openingBalanceCredit) {

                    $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                    $currOpeningBalanceSide = 'dr';
                } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                    $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                    $currOpeningBalanceSide = 'cr';
                }

                $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
                $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

                $closingBalance = 0;
                $closingBalanceSide = 'dr';
                if ($CurrTotalDebit > $CurrTotalCredit) {

                    $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
                    $closingBalanceSide = 'dr';
                } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                    $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                    $closingBalanceSide = 'cr';
                }

                return \App\Utils\Converter::format_in_bdt($closingBalance).' '.ucfirst($closingBalanceSide).'.';
            })

            ->rawColumns(['action', 'opening_balance', 'debit', 'credit', 'closing_balance'])
            ->make(true);
    }

    public function accountClosingBalance($account_id, $user_id = null, $from_date = null, $to_date = null)
    {
        $converter = new \App\Utils\Converter();
        $amounts = '';
        $query = DB::table('account_ledgers')->where('account_ledgers.account_id', $account_id);

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($from_date && $to_date) {

            $gs = DB::table('general_settings')->select('business')->first();
            $accountStartDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

            $fromDateYmd = Carbon::parse($from_date)->startOfDay();
            $toDateYmd = Carbon::parse($to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        if ($user_id) {

            $query->where('account_ledgers.user_id', $user_id);
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } elseif ($fromDateYmd && $toDateYmd && $fromDateYmd <= $accountStartDateYmd) {

            $query->select(
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type = 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type = 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } else {

            $query->select(
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.debit end), 0) as opening_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.credit end), 0) as opening_total_credit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.debit end), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.credit end), 0) as curr_total_credit'),
            );
        }

        $amounts = $query->groupBy('account_ledgers.account_id')->get();

        $openingBalanceDebit = $amounts->sum('opening_total_debit');
        $__openingBalanceDebit = $amounts->sum('opening_total_debit');
        $openingBalanceCredit = $amounts->sum('opening_total_credit');
        $__openingBalanceCredit = $amounts->sum('opening_total_credit');

        $currTotalDebit = $amounts->sum('curr_total_debit');
        $__currTotalDebit = $amounts->sum('curr_total_debit');
        $currTotalCredit = $amounts->sum('curr_total_credit');
        $__currTotalCredit = $amounts->sum('curr_total_credit');

        $currOpeningBalance = 0;
        $currOpeningBalanceSide = 'dr';
        if ($openingBalanceDebit > $openingBalanceCredit) {

            $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
            $currOpeningBalanceSide = 'dr';
        } elseif ($openingBalanceCredit > $openingBalanceDebit) {

            $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
            $currOpeningBalanceSide = 'cr';
        }

        $currTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
        $currTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

        $closingBalance = 0;
        $closingBalanceSide = 'dr';
        if ($currTotalDebit > $currTotalCredit) {

            $closingBalance = $currTotalDebit - $currTotalCredit;
            $closingBalanceSide = 'dr';
        } elseif ($currTotalCredit > $currTotalDebit) {

            $closingBalance = $currTotalCredit - $currTotalDebit;
            $closingBalanceSide = 'cr';
        }

        $allTotalDebit = 0;
        $allTotalCredit = 0;
        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $allTotalDebit = $__currTotalDebit + ($currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0);
            $allTotalCredit = $__currTotalCredit + ($currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0);
        } else {

            $allTotalDebit = $__currTotalDebit + $__openingBalanceDebit;
            $allTotalCredit = $__currTotalCredit + $__openingBalanceCredit;
        }

        return [
            'opening_balance' => $currOpeningBalance ? $currOpeningBalance : 0,
            'opening_balance_side' => $currOpeningBalanceSide,
            'curr_total_debit' => $__currTotalDebit ? $__currTotalDebit : 0,
            'curr_total_credit' => $__currTotalCredit ? $__currTotalCredit : 0,
            'all_total_debit' => $allTotalDebit ? $allTotalDebit : 0,
            'all_total_credit' => $allTotalCredit ? $allTotalCredit : 0,
            'closing_balance' => $closingBalance,
            'closing_balance_side' => $closingBalanceSide,
            'closing_balance_string' => $converter::format_in_bdt($closingBalance).($closingBalanceSide == 'dr' ? ' Dr.' : ' Cr.'),
        ];
    }

    public function addAccount($request, $customerId = null, $supplierId = null)
    {
        $addAccount = new Account();
        $addAccount->name = $request->name;
        $addAccount->phone = $request->phone;
        $addAccount->address = $request->address;
        $addAccount->account_number = $request->account_number ? $request->account_number : null;
        $addAccount->bank_id = $request->bank_id ? $request->bank_id : null;
        $addAccount->bank_code = $request->bank_code ? $request->bank_code : null;
        $addAccount->swift_code = $request->swift_code ? $request->swift_code : null;
        $addAccount->bank_branch = $request->bank_branch ? $request->bank_branch : null;
        $addAccount->bank_address = $request->bank_address ? $request->bank_address : null;
        $addAccount->tax_percent = $request->tax_percent ? $request->tax_percent : 0;
        $addAccount->customer_id = $customerId;
        $addAccount->supplier_id = $supplierId;
        $addAccount->account_group_id = $request->account_group_id;
        $addAccount->account_type = 0;
        $addAccount->opening_balance = $request->opening_balance ? $request->opening_balance : 0;
        $addAccount->balance = $request->opening_balance ? $request->opening_balance : 0;
        $addAccount->opening_balance_type = $request->opening_balance_type;
        $addAccount->remark = $request->remark;
        $addAccount->created_by_id = auth()->user()->id;
        $addAccount->created_at = Carbon::now();
        $addAccount->save();

        return $addAccount;
    }

    public function updateAccount($request, $account)
    {
        $account->name = $request->name;
        $account->phone = $request->phone;
        $account->address = $request->address;
        $account->account_number = $request->account_number ? $request->account_number : null;
        $account->bank_id = $request->bank_id ? $request->bank_id : null;
        $account->bank_code = $request->bank_code ? $request->bank_code : null;
        $account->swift_code = $request->swift_code ? $request->swift_code : null;
        $account->bank_branch = $request->bank_branch ? $request->bank_branch : null;
        $account->bank_address = $request->bank_address ? $request->bank_address : null;
        $account->tax_percent = $request->tax_percent ? $request->tax_percent : 0;
        $account->account_group_id = $request->account_group_id;
        $account->opening_balance = $request->opening_balance ? $request->opening_balance : 0;
        $account->balance = $request->opening_balance ? $request->opening_balance : 0;
        $account->opening_balance_type = $request->opening_balance_type;
        $account->remark = $request->remark;
        $account->save();

        return $account;
    }
}
