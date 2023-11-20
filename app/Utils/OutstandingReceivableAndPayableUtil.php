<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OutstandingReceivableAndPayableUtil
{
    public function outstandingReceivableAndPayable($request, $accountStartDate)
    {
        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $query = DB::table('account_groups')
            ->whereIn('account_groups.sub_sub_group_number', [6, 10])
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('users', 'account_ledgers.user_id', 'users.id');

        if ($request->user_id) {

            $query->where('account_ledgers.user_id', $request->user_id);
        }

        if ($request->sub_sub_group_number) {

            $query->where('account_groups.sub_sub_group_number', $request->sub_sub_group_number);
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'account_groups.sub_sub_group_number',
                'accounts.id as account_id',
                'accounts.name as account_name',
                'users.id as u_id',
                'users.prefix as u_prefix',
                'users.name as u_name',
                'users.last_name as u_last_name',
                DB::raw(
                    "
                        IFNULL(SUM(CASE
                            WHEN timestamp(account_ledgers.date) < '$fromDateYmd' THEN account_ledgers.debit
                            END), 0) AS opening_total_debit,
                        IFNULL(SUM(CASE
                            WHEN timestamp(account_ledgers.date) < '$fromDateYmd' THEN account_ledgers.credit
                            END), 0) AS opening_total_credit,
                        IFNULL(SUM(CASE
                            WHEN timestamp(account_ledgers.date) > '$fromDateYmd'
                            AND timestamp(account_ledgers.date) < '$toDateYmd' THEN account_ledgers.debit
                            END), 0) AS curr_total_debit,
                        IFNULL(SUM(CASE
                            WHEN timestamp(account_ledgers.date) > '$fromDateYmd'
                            AND timestamp(account_ledgers.date) < '$toDateYmd' THEN account_ledgers.credit
                            END), 0) AS curr_total_credit
                    "
                )
            );
        } else {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'account_groups.sub_sub_group_number',
                'accounts.id as account_id',
                'accounts.name as account_name',
                'users.id as u_id',
                'users.prefix as u_prefix',
                'users.name as u_name',
                'users.last_name as u_last_name',
                DB::raw(
                    '
                        IFNULL(
                            SUM(account_ledgers.debit),
                            0
                        ) AS curr_total_debit,

                        IFNULL(
                            SUM(account_ledgers.credit),
                            0
                        ) AS curr_total_credit
                    '
                ),
            );
        }

        return $results = $query
            ->groupBy('account_groups.id')
            ->groupBy('account_groups.name')
            ->groupBy('account_groups.sub_sub_group_number')
            ->groupBy('accounts.id')
            ->groupBy('accounts.name')
            ->groupBy('users.id')
            ->groupBy('users.prefix')
            ->groupBy('users.name')
            ->groupBy('users.last_name')
            ->orderBy('accounts.name')->get();
    }
}
