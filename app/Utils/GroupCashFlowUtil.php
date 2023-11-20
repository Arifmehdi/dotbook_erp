<?php

namespace App\Utils;

use App\Models\AccountGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GroupCashFlowUtil
{
    // public function groupCashFlowDetails($groupId, $groupLevel, $fromDate, $toDate)
    // {
    //     $group = DB::table('account_groups')->where('id', $groupId)->first();

    //     $fromDateYmd = '';
    //     $toDateYmd = '';
    //     if ($fromDate && $toDate) {

    //         $fromDateYmd = Carbon::parse($fromDate)->startOfDay();
    //         $toDateYmd = Carbon::parse($toDate)->endOfDay();
    //     }

    //     $query = DB::table('account_groups')
    //         ->where('account_groups.is_main_group', 0);

    //     if ($groupLevel == 'sub_group_number') {

    //         $query->where('account_groups.' . $groupLevel, $group->{$groupLevel});
    //     } else {

    //         $query->where('account_groups.id', $group->id);
    //     }

    //     $query->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
    //         ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
    //         ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
    //         ->where('account_ledgers.is_cash_flow', 1);

    //     if ($fromDateYmd && $toDateYmd) {

    //         $query->select(
    //             'account_groups.id as group_id',
    //             'account_groups.name as group_name',
    //             'account_groups.main_group_number',
    //             'account_groups.sub_group_number',
    //             'account_groups.sub_sub_group_number',
    //             'parentGroup.name as parent_group_name',
    //             'accounts.id as account_id',
    //             'accounts.name as account_name',
    //             DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.voucher_type != 1 and account_ledgers.voucher_type != 2 and account_ledgers.voucher_type != 3 and account_ledgers.voucher_type != 4 then account_ledgers.debit end), 0) as cash_out"),
    //             DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.voucher_type != 1 and account_ledgers.voucher_type != 2 and account_ledgers.voucher_type != 3 and account_ledgers.voucher_type != 4 then account_ledgers.credit end), 0) as cash_in"),
    //         );
    //     } else {

    //         $query->select(
    //             'account_groups.id as group_id',
    //             'account_groups.name as group_name',
    //             'account_groups.main_group_number',
    //             'account_groups.sub_group_number',
    //             'account_groups.sub_sub_group_number',
    //             'parentGroup.name as parent_group_name',
    //             'accounts.id as account_id',
    //             'accounts.name as account_name',
    //             DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.voucher_type != 1 and account_ledgers.voucher_type != 2 and account_ledgers.voucher_type != 3 and account_ledgers.voucher_type != 4 then account_ledgers.debit end), 0) as cash_out"),
    //             DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.voucher_type != 1 and account_ledgers.voucher_type != 2 and account_ledgers.voucher_type != 3 and account_ledgers.voucher_type != 4 then account_ledgers.credit end), 0) as cash_in"),
    //         );
    //     }

    //     $results = $query
    //         ->groupBy('account_groups.id')
    //         ->groupBy('account_groups.name')
    //         ->groupBy('account_groups.main_group_number')
    //         ->groupBy('account_groups.sub_group_number')
    //         ->groupBy('account_groups.sub_sub_group_number')
    //         ->groupBy('parentGroup.name')
    //         // ->groupBy('account_groups.' . $groupLevel)
    //         ->groupBy('accounts.id')
    //         ->groupBy('accounts.name')
    //         ->get();

    //     $mappedArray = [
    //         'main_group_id' => 1,
    //         'main_group_name' => 'Capital A/c',
    //         'cash_in' => 0,
    //         'cash_out' => 0,
    //         'groups' => [],
    //         'accounts' => []
    //     ];

    //     $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);
    //     return json_decode(json_encode($prepareMappedArray));
    // }

    public function groupCashFlowDetails($groupId, $fromDate, $toDate)
    {
        $fromDateYmd = '';
        $toDateYmd = '';

        if ($fromDate && $toDate) {

            $fromDateYmd = Carbon::parse($fromDate)->startOfDay();
            $toDateYmd = Carbon::parse($toDate)->endOfDay();
        }

        $mainGroup = AccountGroup::query()->with(
            [
                'accounts:id,name,phone,account_number,account_group_id',
                'accounts.accountLedgers:id,is_cash_flow,account_id,date,voucher_type,debit,credit',
                'subgroupsAccountsForOthers:id,name,parent_group_id,sub_group_number,sub_sub_group_number',
                'subgroupsAccountsForOthers.accounts.accountLedgers:id,is_cash_flow,account_id,date,voucher_type,debit,credit',
            ]
        )->select('id', 'name', 'parent_group_id', 'sub_group_number', 'sub_sub_group_number')->where('account_groups.id', $groupId)->first();

        foreach ($mainGroup->accounts as $account) {

            $currentDebit = 0;
            $currentCredit = 0;

            if ($fromDateYmd && $toDateYmd) {

                $date_range = [$fromDateYmd, $toDateYmd];
                $currentDebit = $account->accountLedgers->whereBetween('date', $date_range)->where('is_cash_flow', 1)->sum('debit');
                $currentCredit = $account->accountLedgers->whereBetween('date', $date_range)->where('is_cash_flow', 1)->sum('credit');
            } else {

                $currentDebit = $account->accountLedgers->where('is_cash_flow', 1)->sum('debit');
                $currentCredit = $account->accountLedgers->where('is_cash_flow', 1)->sum('credit');
            }

            $account->cash_out = $currentDebit;
            $account->cash_in = $currentCredit;

            $mainGroup->cash_out += $currentDebit;
            $mainGroup->cash_id += $currentCredit;

            // unset($account->accountLedgers);
        }

        $subGroups = [];
        if (count($mainGroup->subgroupsAccountsForOthers) > 0) {

            $subGroups = $mainGroup->subgroupsAccountsForOthers;
        }

        foreach ($subGroups as $subGroup) {

            foreach ($subGroup->accounts as $account) {

                $currentDebit = 0;
                $currentCredit = 0;

                if ($fromDateYmd && $toDateYmd) {

                    $date_range = [$fromDateYmd, $toDateYmd];
                    $currentDebit = $account->accountLedgers->whereBetween('date', $date_range)->where('is_cash_flow', 1)->sum('debit');
                    $currentCredit = $account->accountLedgers->whereBetween('date', $date_range)->where('is_cash_flow', 1)->sum('credit');
                } else {

                    $currentDebit = $account->accountLedgers->where('is_cash_flow', 1)->sum('debit');
                    $currentCredit = $account->accountLedgers->where('is_cash_flow', 1)->sum('credit');
                }

                $subGroup->cash_out += $currentDebit;
                $subGroup->cash_in += $currentCredit;

                $mainGroup->cash_out += $currentDebit;
                $mainGroup->cash_in += $currentCredit;

                // unset($account->accountLedgers);
            }

            if (count($subGroup->subgroupsAccountsForOthers) > 0) {

                $this->subGroups($subGroup->subgroupsAccountsForOthers, $mainGroup, $subGroup, $fromDateYmd, $toDateYmd);
            }
        }

        foreach ($mainGroup->subgroupsAccountsForOthers as $subGroup) {

            if (count($subGroup->subgroupsAccountsForOthers) > 0) {

                unset($subGroup->accounts);
                unset($subGroup->subgroupsAccountsForOthers);
            }
        }

        return $mainGroup;
    }

    private function subGroups($subGroups, $mainGroup, $mainSubGroup, $fromDateYmd, $toDateYmd)
    {
        foreach ($subGroups as $subGroup) {

            if (count($subGroup->accounts) > 0) {

                foreach ($subGroup->accounts as $account) {

                    $currentDebitOpeningBalance = 0;
                    $currentCreditOpeningBalance = 0;
                    $currentDebit = 0;
                    $currentCredit = 0;

                    if ($fromDateYmd && $toDateYmd) {

                        $date_range = [$fromDateYmd, $toDateYmd];
                        $currentDebit = $account->accountLedgers->whereBetween('date', $date_range)->where('is_cash_flow', 1)->sum('debit');
                        $currentCredit = $account->accountLedgers->whereBetween('date', $date_range)->where('is_cash_flow', 1)->sum('credit');
                    } else {

                        $currentDebit = $account->accountLedgers->where('is_cash_flow', 1)->sum('debit');
                        $currentCredit = $account->accountLedgers->where('is_cash_flow', 1)->sum('credit');
                    }

                    $mainSubGroup->cash_out += $currentDebit;
                    $mainSubGroup->cash_in += $currentCredit;

                    $mainGroup->cash_out += $currentDebit;
                    $mainGroup->cash_in += $currentCredit;

                    // unset($account->accountLedgers);
                }
            }

            if (count($subGroup->subgroupsAccountsForOthers) > 0) {

                $this->subGroups($subGroup->subgroupsAccountsForOthers, $mainGroup, $mainSubGroup, $fromDateYmd, $toDateYmd);
            }
        }
    }
}
