<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfitLossAccountUtil
{
    public function salesAccountBalance($request, $accountStartDate)
    {
        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $accountGroup = '';
        $query = DB::table('account_groups')->where('account_groups.sub_group_number', 15)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } else {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
            );
        }

        $results = $query
            ->groupBy('account_groups.id')
            ->groupBy('account_groups.name')
            ->groupBy('accounts.id')
            ->groupBy('accounts.name')
            ->get();

        $groupDebitTotal = 0;
        $groupCreditTotal = 0;
        $groupName = '';
        foreach ($results as $index => $res) {

            if ($index == 0) {

                $groupName = $res->group_name;
            }

            $groupName = $res->group_name;
            $openingBalanceDebit = isset($res->opening_total_debit) ? (float) $res->opening_total_debit : 0;
            $openingBalanceCredit = isset($res->opening_total_credit) ? (float) $res->opening_total_credit : 0;

            $CurrTotalDebit = (float) $res->curr_total_debit;
            $CurrTotalCredit = (float) $res->curr_total_credit;

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = 'dr';

            if ($openingBalanceDebit > $openingBalanceCredit) {

                $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
            } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                $currOpeningBalanceSide = 'cr';
            }

            $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
            $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

            $groupDebitTotal += $CurrTotalDebit;
            $groupCreditTotal += $CurrTotalCredit;

            $closingBalance = 0;
            $closingBalanceSide = 'dr';
            if ($CurrTotalDebit > $CurrTotalCredit) {

                $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
            } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                $closingBalanceSide = 'cr';
            }

            $res->closing_balance = $closingBalance;
            $res->closing_balance_side = $closingBalanceSide;
        }

        $groupClosingBalance = 0;
        $groupClosingBalanceSide = 'dr';
        if ($groupDebitTotal > $groupCreditTotal) {

            $groupClosingBalance = $groupDebitTotal - $groupCreditTotal;
        } elseif ($groupCreditTotal > $groupDebitTotal) {

            $groupClosingBalance = $groupCreditTotal - $groupDebitTotal;
            $groupClosingBalanceSide = 'cr';
        }

        return ['results' => $results, 'groupName' => $groupName, 'groupClosingBalance' => $groupClosingBalance, 'groupClosingBalanceSide' => $groupClosingBalanceSide];
    }

    public function purchaseAccountBalance($request, $accountStartDate)
    {
        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $accountGroup = '';
        $query = DB::table('account_groups')->where('account_groups.sub_group_number', 12)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } else {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
            );
        }

        $results = $query
            ->groupBy('account_groups.id')
            ->groupBy('account_groups.name')
            ->groupBy('accounts.id')
            ->groupBy('accounts.name')
            ->get();

        $groupDebitTotal = 0;
        $groupCreditTotal = 0;
        $groupName = '';
        foreach ($results as $index => $res) {

            if ($index == 0) {

                $groupName = $res->group_name;
            }

            $openingBalanceDebit = isset($res->opening_total_debit) ? (float) $res->opening_total_debit : 0;
            $openingBalanceCredit = isset($res->opening_total_credit) ? (float) $res->opening_total_credit : 0;

            $CurrTotalDebit = (float) $res->curr_total_debit;
            $CurrTotalCredit = (float) $res->curr_total_credit;

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = 'dr';

            if ($openingBalanceDebit > $openingBalanceCredit) {

                $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
            } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                $currOpeningBalanceSide = 'cr';
            }

            $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
            $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

            $groupDebitTotal += $CurrTotalDebit;
            $groupCreditTotal += $CurrTotalCredit;

            $closingBalance = 0;
            $closingBalanceSide = 'dr';
            if ($CurrTotalDebit > $CurrTotalCredit) {

                $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
            } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                $closingBalanceSide = 'cr';
            }

            $res->closing_balance = $closingBalance;
            $res->closing_balance_side = $closingBalanceSide;
        }

        $groupClosingBalance = 0;
        $groupClosingBalanceSide = 'dr';
        if ($groupDebitTotal > $groupCreditTotal) {

            $groupClosingBalance = $groupDebitTotal - $groupCreditTotal;
        } elseif ($groupCreditTotal > $groupDebitTotal) {

            $groupClosingBalance = $groupCreditTotal - $groupDebitTotal;
            $groupClosingBalanceSide = 'cr';
        }

        return ['results' => $results, 'groupName' => $groupName, 'groupClosingBalance' => $groupClosingBalance, 'groupClosingBalanceSide' => $groupClosingBalanceSide];
    }

    public function directExpenseAccountBalance($request, $accountStartDate)
    {
        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $accountGroup = '';
        $query = DB::table('account_groups')->where('account_groups.sub_group_number', 10)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } else {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
            );
        }

        $results = $query
            ->groupBy('account_groups.id')
            ->groupBy('account_groups.name')
            ->groupBy('accounts.id')
            ->groupBy('accounts.name')
            ->get();

        $groupDebitTotal = 0;
        $groupCreditTotal = 0;
        $groupName = '';

        foreach ($results as $index => $res) {

            if ($index == 0) {

                $groupName = $res->group_name;
            }

            $openingBalanceDebit = isset($res->opening_total_debit) ? (float) $res->opening_total_debit : 0;
            $openingBalanceCredit = isset($res->opening_total_credit) ? (float) $res->opening_total_credit : 0;

            $CurrTotalDebit = (float) $res->curr_total_debit;
            $CurrTotalCredit = (float) $res->curr_total_credit;

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = 'dr';

            if ($openingBalanceDebit > $openingBalanceCredit) {

                $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
            } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                $currOpeningBalanceSide = 'cr';
            }

            $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
            $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

            $groupDebitTotal += $CurrTotalDebit;
            $groupCreditTotal += $CurrTotalCredit;

            $closingBalance = 0;
            $closingBalanceSide = 'dr';
            if ($CurrTotalDebit > $CurrTotalCredit) {

                $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
            } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                $closingBalanceSide = 'cr';
            }

            $res->closing_balance = $closingBalance;
            $res->closing_balance_side = $closingBalanceSide;
        }

        $groupClosingBalance = 0;
        $groupClosingBalanceSide = 'dr';
        if ($groupDebitTotal > $groupCreditTotal) {

            $groupClosingBalance = $groupDebitTotal - $groupCreditTotal;
        } elseif ($groupCreditTotal > $groupDebitTotal) {

            $groupClosingBalance = $groupCreditTotal - $groupDebitTotal;
            $groupClosingBalanceSide = 'cr';
        }

        return ['results' => $results, 'groupName' => $groupName, 'groupClosingBalance' => $groupClosingBalance, 'groupClosingBalanceSide' => $groupClosingBalanceSide];
    }

    public function indirectExpenseAccountBalance($request, $accountStartDate)
    {
        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $accountGroup = '';
        $query = DB::table('account_groups')->where('account_groups.is_main_group', 0)->where('account_groups.sub_group_number', 11)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } else {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
            );
        }

        $results = $query
            ->groupBy('account_groups.id')
            ->groupBy('account_groups.name')
            ->groupBy('accounts.id')
            ->groupBy('accounts.name')
            ->get();

        $groupDebitTotal = 0;
        $groupCreditTotal = 0;
        $groupName = '';

        foreach ($results as $index => $res) {

            if ($index == 0) {

                $groupName = $res->group_name;
            }

            $openingBalanceDebit = isset($res->opening_total_debit) ? (float) $res->opening_total_debit : 0;
            $openingBalanceCredit = isset($res->opening_total_credit) ? (float) $res->opening_total_credit : 0;

            $CurrTotalDebit = (float) $res->curr_total_debit;
            $CurrTotalCredit = (float) $res->curr_total_credit;

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = 'dr';

            if ($openingBalanceDebit > $openingBalanceCredit) {

                $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
            } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                $currOpeningBalanceSide = 'cr';
            }

            $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
            $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

            $groupDebitTotal += $CurrTotalDebit;
            $groupCreditTotal += $CurrTotalCredit;

            $closingBalance = 0;
            $closingBalanceSide = 'dr';
            if ($CurrTotalDebit > $CurrTotalCredit) {

                $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
            } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                $closingBalanceSide = 'cr';
            }

            $res->closing_balance = $closingBalance;
            $res->closing_balance_side = $closingBalanceSide;

            $index++;
        }

        $groupClosingBalance = 0;
        $groupClosingBalanceSide = 'dr';
        if ($groupDebitTotal > $groupCreditTotal) {

            $groupClosingBalance = $groupDebitTotal - $groupCreditTotal;
        } elseif ($groupCreditTotal > $groupDebitTotal) {

            $groupClosingBalance = $groupCreditTotal - $groupDebitTotal;
            $groupClosingBalanceSide = 'cr';
        }

        return ['results' => $results, 'groupName' => $groupName, 'groupClosingBalance' => $groupClosingBalance, 'groupClosingBalanceSide' => $groupClosingBalanceSide];
    }

    public function directIncomesAccountBalance($request, $accountStartDate)
    {
        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $accountGroup = '';
        $query = DB::table('account_groups')->where('account_groups.is_main_group', 0)->where('account_groups.sub_group_number', 13)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } else {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
            );
        }

        $results = $query
            ->groupBy('account_groups.id')
            ->groupBy('account_groups.name')
            ->groupBy('accounts.id')
            ->groupBy('accounts.name')
            ->get();

        $groupDebitTotal = 0;
        $groupCreditTotal = 0;
        $groupName = '';
        foreach ($results as $index => $res) {

            if ($index == 0) {

                $groupName = $res->group_name;
            }

            $openingBalanceDebit = isset($res->opening_total_debit) ? (float) $res->opening_total_debit : 0;
            $openingBalanceCredit = isset($res->opening_total_credit) ? (float) $res->opening_total_credit : 0;

            $CurrTotalDebit = (float) $res->curr_total_debit;
            $CurrTotalCredit = (float) $res->curr_total_credit;

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = 'dr';

            if ($openingBalanceDebit > $openingBalanceCredit) {

                $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
            } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                $currOpeningBalanceSide = 'cr';
            }

            $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
            $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

            $groupDebitTotal += $CurrTotalDebit;
            $groupCreditTotal += $CurrTotalCredit;

            $closingBalance = 0;
            $closingBalanceSide = 'dr';
            if ($CurrTotalDebit > $CurrTotalCredit) {

                $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
            } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                $closingBalanceSide = 'cr';
            }

            $res->closing_balance = $closingBalance;
            $res->closing_balance_side = $closingBalanceSide;
        }

        $groupClosingBalance = 0;
        $groupClosingBalanceSide = 'dr';
        if ($groupDebitTotal > $groupCreditTotal) {

            $groupClosingBalance = $groupDebitTotal - $groupCreditTotal;
        } elseif ($groupCreditTotal > $groupDebitTotal) {

            $groupClosingBalance = $groupCreditTotal - $groupDebitTotal;
            $groupClosingBalanceSide = 'cr';
        }

        return ['results' => $results, 'groupName' => $groupName, 'groupClosingBalance' => $groupClosingBalance, 'groupClosingBalanceSide' => $groupClosingBalanceSide];
    }

    public function indirectIncomesAccountBalance($request, $accountStartDate)
    {
        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $accountGroup = '';
        $query = DB::table('account_groups')->where('account_groups.sub_group_number', 14)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } else {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
            );
        }

        $results = $query
            ->groupBy('account_groups.id')
            ->groupBy('account_groups.name')
            ->groupBy('accounts.id')
            ->groupBy('accounts.name')
            ->get();

        $groupDebitTotal = 0;
        $groupCreditTotal = 0;
        $groupName = '';
        foreach ($results as $index => $res) {

            if ($index == 0) {

                $groupName = $res->group_name;
            }

            $openingBalanceDebit = isset($res->opening_total_debit) ? (float) $res->opening_total_debit : 0;
            $openingBalanceCredit = isset($res->opening_total_credit) ? (float) $res->opening_total_credit : 0;

            $CurrTotalDebit = (float) $res->curr_total_debit;
            $CurrTotalCredit = (float) $res->curr_total_credit;

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = 'dr';

            if ($openingBalanceDebit > $openingBalanceCredit) {

                $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
            } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                $currOpeningBalanceSide = 'cr';
            }

            $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
            $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

            $groupDebitTotal += $CurrTotalDebit;
            $groupCreditTotal += $CurrTotalCredit;

            $closingBalance = 0;
            $closingBalanceSide = 'dr';
            if ($CurrTotalDebit > $CurrTotalCredit) {

                $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
            } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                $closingBalanceSide = 'cr';
            }

            $res->closing_balance = $closingBalance;
            $res->closing_balance_side = $closingBalanceSide;
        }

        $groupClosingBalance = 0;
        $groupClosingBalanceSide = 'dr';
        if ($groupDebitTotal > $groupCreditTotal) {

            $groupClosingBalance = $groupDebitTotal - $groupCreditTotal;
        } elseif ($groupCreditTotal > $groupDebitTotal) {

            $groupClosingBalance = $groupCreditTotal - $groupDebitTotal;
            $groupClosingBalanceSide = 'cr';
        }

        return ['results' => $results, 'groupName' => $groupName, 'groupClosingBalance' => $groupClosingBalance, 'groupClosingBalanceSide' => $groupClosingBalanceSide];
    }

    public function openingStock()
    {
        $productOpeningStock = DB::table('product_opening_stocks')
            ->select(DB::raw('IFNULL(sum(subtotal), 0) as po_stock_value'))
            ->groupBy('product_opening_stocks.product_id')->get();

        return $productOpeningStock->sum('po_stock_value');
    }

    public function closingStock($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $productOpeningStock = DB::table('product_opening_stocks')
            ->select(DB::raw('sum(subtotal) as total_value'))
            ->groupBy('product_opening_stocks.product_id')->get();

        $productPurchase = '';
        $productPurchaseQ = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->where('purchases.receive_stock_id', null)
            ->where('purchase_products.opening_stock_id', null)
            ->where('purchase_products.production_id', null)
            ->where('purchase_products.sale_return_product_id', null)
            ->where('purchase_products.daily_stock_product_id', null);

        if ($fromDateYmd && $toDateYmd) {

            $date_range = [$fromDateYmd, $toDateYmd];
            $productPurchaseQ->whereBetween('purchases.report_date', $date_range);
        }

        $productPurchase = $productPurchaseQ->select(DB::raw('IFNULL(SUM(purchase_products.line_total), 0) as total_value'))
            ->groupBy('purchase_products.product_id')->get();

        $production = '';
        $productionQ = DB::table('productions')->where('productions.is_final', 1);
        if ($fromDateYmd && $toDateYmd) {

            $date_range = [$fromDateYmd, $toDateYmd];
            $productionQ->whereBetween('productions.report_date', $date_range);
        }
        $production = $productionQ->select(DB::raw('IFNULL(SUM(productions.total_cost), 0) as total_value'))->groupBy('productions.product_id')->get();

        $usedProduction = '';
        $usedProductionQ = DB::table('production_ingredients')
            ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
            ->where('productions.is_final', 1);

        if ($fromDateYmd && $toDateYmd) {

            $date_range = [$fromDateYmd, $toDateYmd];
            $usedProductionQ->whereBetween('productions.report_date', $date_range);
        }
        $usedProduction = $usedProductionQ->select(DB::raw('IFNULL(SUM(production_ingredients.subtotal), 0) as total_value'))->groupBy('production_ingredients.product_id')->get();

        $productSale = '';
        $productSaleQ = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->where('sales.status', 1);

        if ($fromDateYmd && $toDateYmd) {

            $date_range = [$fromDateYmd, $toDateYmd];
            $productSaleQ->whereBetween('sales.report_date', $date_range);
        }
        $productSale = $productSaleQ->select(DB::raw('sum(sale_products.quantity * sale_products.unit_cost_inc_tax) as total_value'))->groupBy('sale_products.product_id')->get();

        $purchaseReturn = '';
        $purchaseReturnQ = DB::table('purchase_return_products')
            ->leftJoin('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd) {

            $date_range = [$fromDateYmd, $toDateYmd];
            $purchaseReturnQ->whereBetween('purchase_returns.report_date', $date_range);
        }

        $purchaseReturn = $purchaseReturnQ->select(DB::raw('sum(return_subtotal) as total_value'))->groupBy('product_id')->get();

        $saleReturn = '';
        $saleReturnQ = DB::table('sale_return_products')
            ->leftJoin('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd) {

            $date_range = [$fromDateYmd, $toDateYmd];
            $saleReturnQ->whereBetween('sale_returns.report_date', $date_range);
        }
        $saleReturn = $saleReturnQ->select(DB::raw('sum(sale_return_products.return_subtotal) as total_value'))->groupBy('product_id')->get();

        $adjustment = '';
        $adjustmentQ = DB::table('stock_adjustment_products')
            ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd) {

            $date_range = [$fromDateYmd, $toDateYmd];
            $adjustmentQ->whereBetween('stock_adjustments.date_ts', $date_range);
        }

        $adjustment = $adjustmentQ->select(DB::raw('sum(stock_adjustment_products.subtotal) as total_value'))->groupBy('stock_adjustment_products.product_id')->get();

        $stockIssue = '';
        $stockIssueQ = DB::table('stock_issue_products')
            ->leftJoin('stock_issues', 'stock_issue_products.stock_issue_id', 'stock_issues.id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd) {

            $date_range = [$fromDateYmd, $toDateYmd];
            $stockIssueQ->whereBetween('stock_issues.report_date', $date_range);
        }
        $stockIssue = $stockIssueQ->select(DB::raw('sum(stock_issue_products.subtotal) as total_value'))
            ->groupBy('stock_issue_products.product_id')->get();

        $dailyStock = '';
        $dailyStockQ = DB::table('daily_stock_products')
            ->leftJoin('daily_stocks', 'daily_stock_products.daily_stock_id', 'daily_stocks.id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd) {

            $date_range = [$fromDateYmd, $toDateYmd];
            $dailyStockQ->whereBetween('daily_stocks.report_date', $date_range);
        }

        $dailyStock = $dailyStockQ
            ->select(DB::raw('SUM(daily_stock_products.subtotal) as total_value'))
            ->groupBy('daily_stock_products.product_id')
            ->get();

        $receivedStock = '';
        $receivedStockQ = DB::table('receive_stocks')
            ->join('purchases', 'receive_stocks.id', 'purchases.receive_stock_id')
            ->leftJoin('purchase_products', 'purchase_products.id', 'purchase_products.purchase_id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd) {

            $date_range = [$fromDateYmd, $toDateYmd];
            $receivedStockQ->whereBetween('receive_stocks.date_ts', $date_range);
        }

        $receivedStock = $receivedStockQ->select(DB::raw('SUM(purchase_products.line_total) as total_value'))->groupBy('product_id')->get();

        $closingStock = $productPurchase->sum('total_value')
            + $productOpeningStock->sum('total_value')
            + $saleReturn->sum('total_value')
            - $productSale->sum('total_value')
            - $adjustment->sum('total_value')
            - $purchaseReturn->sum('total_value')
            + $production->sum('total_value')
            + $receivedStock->sum('total_value')
            - $usedProduction->sum('total_value')
            - $stockIssue->sum('total_value')
            + $dailyStock->sum('total_value');

        return json_decode(json_encode(['opening_stock' => $productOpeningStock->sum('total_value'), 'closing_stock' => $closingStock]));
    }

    public function netProfitLoss($request, $accountStartDate)
    {
        $salesAccountBalance = $this->salesAccountBalance($request, $accountStartDate);
        $purchaseAccountBalance = $this->purchaseAccountBalance($request, $accountStartDate);
        $directExpenseAccountBalance = $this->directExpenseAccountBalance($request, $accountStartDate);
        $directIncomesAccountBalance = $this->directIncomesAccountBalance($request, $accountStartDate);
        $openingStock = $this->closingStock($request)->opening_stock;
        $closingStock = $this->closingStock($request)->closing_stock;

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

        $indirectExpenseAccountBalance = $this->indirectExpenseAccountBalance($request, $accountStartDate);
        $indirectIncomesAccountBalance = $this->indirectIncomesAccountBalance($request, $accountStartDate);

        $netLoss = 0;
        $netProfit = 0;
        $netProfitLossSide = 'cr';
        if ($grossProfitOrLossSide == 'dr') {

            $netLoss += $grossProfitOrLoss;
            $netLoss += $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $netLoss += $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
            $netLoss -= $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $netLoss -= $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
            $netProfitLossSide = 'dr';
        } elseif ($grossProfitOrLossSide == 'cr') {

            $netProfit += $grossProfitOrLoss;
            $netProfit += $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $netProfit += $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
            $netProfit -= $indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectExpenseAccountBalance['groupClosingBalance'] : 0;
            $netProfit -= $indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' ? $indirectIncomesAccountBalance['groupClosingBalance'] : 0;
            $netProfitLossSide = 'cr';
        }

        return [
            'netLoss' => $netLoss,
            'netProfit' => $netProfit,
            'netProfitLossSide' => $netProfitLossSide,
        ];
    }
}
