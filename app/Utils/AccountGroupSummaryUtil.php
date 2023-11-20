<?php

namespace App\Utils;

use App\Models\AccountGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountGroupSummaryUtil
{
    // public function accountGroupSummaryViewDate($groupId, $groupLevel, $fromDate, $toDate, $accountStartDate)
    // {
    //     $group = DB::table('account_groups')->where('id', $groupId)->first();

    //     $accountStartDateYmd = '';
    //     $fromDateYmd = '';
    //     $toDateYmd = '';
    //     if ($fromDate && $toDate) {

    //         $fromDateYmd = Carbon::parse($fromDate)->startOfDay();
    //         $toDateYmd = Carbon::parse($toDate)->endOfDay();
    //         $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
    //     }

    //     $query = DB::table('account_groups')
    //         ->where('account_groups.is_main_group', 0);

    //     $query->where('account_groups.id', $group->id);

    //     // if ($groupLevel == 'sub_sub_group_number' && $group->sub_sub_group_number == null) {

    //     //     $query->where('account_groups.id', $group->id);
    //     // } else {

    //     //     $query->where('account_groups.' . $groupLevel, $group->{$groupLevel});
    //     // }

    //     $query->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
    //         ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

    //     if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

    //         $query->select(
    //             'account_groups.id as group_id',
    //             'account_groups.name as group_name',
    //             'account_groups.main_group_number',
    //             'account_groups.sub_group_number',
    //             'account_groups.sub_sub_group_number',
    //             'accounts.id as account_id',
    //             'accounts.name as account_name',
    //             DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
    //             DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
    //             DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
    //             DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
    //         );
    //     } else {

    //         $query->select(
    //             'account_groups.id as group_id',
    //             'account_groups.name as group_name',
    //             'account_groups.main_group_number',
    //             'account_groups.sub_group_number',
    //             'account_groups.sub_sub_group_number',
    //             'accounts.id as account_id',
    //             'accounts.name as account_name',
    //             DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.debit end), 0) as opening_total_debit"),
    //             DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.credit end), 0) as opening_total_credit"),
    //             DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.debit end), 0) as curr_total_debit"),
    //             DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.credit end), 0) as curr_total_credit"),
    //         );
    //     }

    //     $results = $query
    //         ->groupBy('account_groups.id')
    //         ->groupBy('account_groups.name')
    //         ->groupBy('account_groups.main_group_number')
    //         ->groupBy( 'account_groups.sub_group_number')
    //         ->groupBy( 'account_groups.sub_sub_group_number')
    //         // ->groupBy('account_groups.' . $groupLevel)
    //         ->groupBy('accounts.id')
    //         ->groupBy('accounts.name')
    //         ->get();

    //     $mappedArray = [
    //         'main_group_id' => 1,
    //         'main_group_name' => 'Capital A/c',
    //         'opening_total_debit' => 0,
    //         'opening_total_credit' => 0,
    //         'curr_total_debit' => 0,
    //         'curr_total_credit' => 0,
    //         'groups' => [],
    //         'accounts' => []
    //     ];

    //     $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);
    //     $formattedArray = json_decode(json_encode($prepareMappedArray));

    //     $this->mainGroupClosingBalance($formattedArray, 'dr', 0);
    //     $this->subGroupClosingBalance($formattedArray, 'dr');
    //     $this->mainGroupAccountClosingBalance($formattedArray, 'dr');

    //     return $formattedArray;
    // }

    public function accountGroupSummaryViewDate($groupId, $fromDate, $toDate, $accountStartDate)
    {
        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';

        if ($fromDate && $toDate) {

            $fromDateYmd = Carbon::parse($fromDate)->startOfDay();
            $toDateYmd = Carbon::parse($toDate)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $mainGroup = AccountGroup::query()->with(
            [
                'accounts:id,name,phone,account_number,account_group_id',
                'accounts.accountLedgers:id,account_id,date,voucher_type,debit,credit',
                'subgroupsAccountsForOthers:id,name,parent_group_id,sub_group_number,sub_sub_group_number',
                'subgroupsAccountsForOthers.accounts.accountLedgers:id,date,account_id,voucher_type,debit,credit',
            ]
        )->select('id', 'name', 'parent_group_id', 'sub_group_number', 'sub_sub_group_number')->where('account_groups.id', $groupId)->first();

        foreach ($mainGroup->accounts as $account) {

            $currentDebitOpeningBalance = 0;
            $currentCreditOpeningBalance = 0;
            $currentDebit = 0;
            $currentCredit = 0;

            if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

                $currentDebitOpeningBalance = $account->accountLedgers->where('date', '<', $fromDateYmd)->sum('debit');
                $currentCreditOpeningBalance = $account->accountLedgers->where('date', '<', $fromDateYmd)->sum('credit');

                $date_range = [$fromDateYmd, $toDateYmd];
                $currentDebit = $account->accountLedgers->whereBetween('date', $date_range)->sum('debit');
                $currentCredit = $account->accountLedgers->whereBetween('date', $date_range)->sum('credit');
            } elseif ($fromDateYmd && $toDateYmd && $fromDateYmd <= $accountStartDateYmd) {

                $date_range = [$fromDateYmd, $toDateYmd];

                $currentDebitOpeningBalance = $account->accountLedgers->where('voucher_type', 0)->whereBetween('date', $date_range)->sum('debit');
                $currentCreditOpeningBalance = $account->accountLedgers->where('voucher_type', 0)->whereBetween('date', $date_range)->sum('credit');

                $currentDebit = $account->accountLedgers->where('voucher_type', '!=', 0)->whereBetween('date', $date_range)->sum('debit');
                $currentCredit = $account->accountLedgers->where('voucher_type', '!=', 0)->whereBetween('date', $date_range)->sum('credit');
            } else {

                $currentDebitOpeningBalance = $account->accountLedgers->where('voucher_type', 0)->sum('debit');
                $currentCreditOpeningBalance = $account->accountLedgers->where('voucher_type', 0)->sum('credit');

                $currentDebit = $account->accountLedgers->where('voucher_type', '!=', 0)->sum('debit');
                $currentCredit = $account->accountLedgers->where('voucher_type', '!=', 0)->sum('credit');
            }

            $account->opening_total_debit = $currentDebitOpeningBalance;
            $account->opening_total_credit = $currentCreditOpeningBalance;
            $account->curr_total_debit = $currentDebit;
            $account->curr_total_credit = $currentCredit;

            $mainGroup->opening_total_debit += $currentDebitOpeningBalance;
            $mainGroup->opening_total_credit += $currentCreditOpeningBalance;
            $mainGroup->curr_total_debit += $currentDebit;
            $mainGroup->curr_total_credit += $currentCredit;

            unset($account->accountLedgers);
        }

        $subGroups = [];
        if (count($mainGroup->subgroupsAccountsForOthers) > 0) {

            $subGroups = $mainGroup->subgroupsAccountsForOthers;
        }

        foreach ($subGroups as $subGroup) {

            foreach ($subGroup->accounts as $account) {

                $currentDebitOpeningBalance = 0;
                $currentCreditOpeningBalance = 0;
                $currentDebit = 0;
                $currentCredit = 0;

                if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

                    $currentDebitOpeningBalance = $account->accountLedgers->where('date', '<', $fromDateYmd)->sum('debit');

                    $currentCreditOpeningBalance = $account->accountLedgers->where('date', '<', $fromDateYmd)->sum('credit');

                    $date_range = [$fromDateYmd, $toDateYmd];
                    $currentDebit = $account->accountLedgers->whereBetween('date', $date_range)->sum('debit');
                    $currentCredit = $account->accountLedgers->whereBetween('date', $date_range)->sum('credit');
                } elseif ($fromDateYmd && $toDateYmd && $fromDateYmd <= $accountStartDateYmd) {

                    $date_range = [$fromDateYmd, $toDateYmd];
                    $currentDebitOpeningBalance = $account->accountLedgers->where('voucher_type', 0)->whereBetween('date', $date_range)->sum('debit');
                    $currentCreditOpeningBalance = $account->accountLedgers->where('voucher_type', 0)->whereBetween('date', $date_range)->sum('credit');

                    $currentDebit = $account->accountLedgers->where('voucher_type', '!=', 0)->whereBetween('date', $date_range)->sum('debit');
                    $currentCredit = $account->accountLedgers->where('voucher_type', '!=', 0)->whereBetween('date', $date_range)->sum('credit');
                } else {

                    $currentDebitOpeningBalance = $account->accountLedgers->where('voucher_type', 0)->sum('debit');
                    $currentCreditOpeningBalance = $account->accountLedgers->where('voucher_type', 0)->sum('credit');

                    $currentDebit = $account->accountLedgers->where('voucher_type', '!=', 0)->sum('debit');
                    $currentCredit = $account->accountLedgers->where('voucher_type', '!=', 0)->sum('credit');
                }

                $subGroup->opening_total_debit += $currentDebitOpeningBalance;
                $subGroup->opening_total_credit += $currentCreditOpeningBalance;
                $subGroup->curr_total_debit += $currentDebit;
                $subGroup->curr_total_credit += $currentCredit;

                $mainGroup->opening_total_debit += $currentDebitOpeningBalance;
                $mainGroup->opening_total_credit += $currentCreditOpeningBalance;
                $mainGroup->curr_total_debit += $currentDebit;
                $mainGroup->curr_total_credit += $currentCredit;

                unset($account->accountLedgers);
            }

            if (count($subGroup->subgroupsAccountsForOthers) > 0) {

                $this->subGroups($subGroup->subgroupsAccountsForOthers, $mainGroup, $subGroup, $fromDateYmd, $toDateYmd, $accountStartDateYmd);
            }
        }

        foreach ($mainGroup->subgroupsAccountsForOthers as $subGroup) {

            if (count($subGroup->subgroupsAccountsForOthers) > 0) {

                unset($subGroup->accounts);
                unset($subGroup->subgroupsAccountsForOthers);
            }
        }

        $this->prepareClosingBalance($mainGroup, 'dr', $fromDate, $toDate);

        return $mainGroup;
    }

    private function subGroups($subGroups, $mainGroup, $mainSubGroup, $fromDateYmd, $toDateYmd, $accountStartDateYmd)
    {
        foreach ($subGroups as $subGroup) {

            if (count($subGroup->accounts) > 0) {

                foreach ($subGroup->accounts as $account) {

                    $currentDebitOpeningBalance = 0;
                    $currentCreditOpeningBalance = 0;
                    $currentDebit = 0;
                    $currentCredit = 0;

                    if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

                        $currentDebitOpeningBalance = $account->accountLedgers->where('date', '<', $fromDateYmd)->sum('debit');

                        $currentCreditOpeningBalance = $account->accountLedgers->where('date', '<', $fromDateYmd)->sum('credit');

                        $date_range = [$fromDateYmd, $toDateYmd];
                        $currentDebit = $account->accountLedgers->whereBetween('date', $date_range)->sum('debit');
                        $currentCredit = $account->accountLedgers->whereBetween('date', $date_range)->sum('credit');
                    } elseif ($fromDateYmd && $toDateYmd && $fromDateYmd <= $accountStartDateYmd) {

                        $date_range = [$fromDateYmd, $toDateYmd];

                        $currentDebitOpeningBalance = $account->accountLedgers->where('voucher_type', 0)->whereBetween('date', $date_range)->sum('debit');
                        $currentCreditOpeningBalance = $account->accountLedgers->where('voucher_type', 0)->whereBetween('date', $date_range)->sum('credit');

                        $currentDebit = $account->accountLedgers->where('voucher_type', '!=', 0)->whereBetween('date', $date_range)->sum('debit');
                        $currentCredit = $account->accountLedgers->where('voucher_type', '!=', 0)->whereBetween('date', $date_range)->sum('credit');
                    } else {

                        $currentDebitOpeningBalance = $account->accountLedgers->where('voucher_type', 0)->sum('debit');
                        $currentCreditOpeningBalance = $account->accountLedgers->where('voucher_type', 0)->sum('credit');

                        $currentDebit = $account->accountLedgers->where('voucher_type', '!=', 0)->sum('debit');
                        $currentCredit = $account->accountLedgers->where('voucher_type', '!=', 0)->sum('credit');
                    }

                    $mainSubGroup->opening_total_debit += $currentDebitOpeningBalance;
                    $mainSubGroup->opening_total_credit += $currentCreditOpeningBalance;
                    $mainSubGroup->curr_total_debit += $currentDebit;
                    $mainSubGroup->curr_total_credit += $currentCredit;

                    $mainGroup->opening_total_debit += $currentDebitOpeningBalance;
                    $mainGroup->opening_total_credit += $currentCreditOpeningBalance;
                    $mainGroup->curr_total_debit += $currentDebit;
                    $mainGroup->curr_total_credit += $currentCredit;

                    unset($account->accountLedgers);
                }
            }

            if (count($subGroup->subgroupsAccountsForOthers) > 0) {

                $this->subGroups($subGroup->subgroupsAccountsForOthers, $mainGroup, $mainSubGroup, $fromDateYmd, $toDateYmd, $accountStartDateYmd);
            }
        }
    }

    public function prepareClosingBalance($mainGroup, $defaultBalanceSide, $fromDate = null, $toDate = null)
    {
        $this->mainGroupClosingStock($mainGroup, $defaultBalanceSide, $fromDate, $toDate);
        $this->mainGroupAccountsBalance($mainGroup->accounts, $defaultBalanceSide);
        $this->subGroupsBalance($mainGroup->subgroupsAccountsForOthers, $defaultBalanceSide, $fromDate, $toDate);
    }

    private function mainGroupClosingStock($mainGroup, $defaultBalanceSide, $fromDate, $toDate)
    {
        $dates = ['from_date' => $fromDate, 'to_date' => $toDate];
        $request = json_decode(json_encode($dates));

        $closingStock = 0;
        $openingStock = 0;
        if ($mainGroup->sub_group_number == 1 && $mainGroup->sub_sub_group_number == null) {

            $profitLossAccountUtil = new \App\Utils\ProfitLossAccountUtil();
            $closingStock = $profitLossAccountUtil->closingStock($request)->closing_stock;
            $openingStock = $profitLossAccountUtil->closingStock($request)->opening_stock;

            $arr = [
                'id' => null,
                'closing_stock_balance' => 1,
                'name' => __('menu.closing_stock'),
                'parent_group_id' => null,
                'sub_group_number' => null,
                'sub_sub_group_number' => null,
                'opening_total_debit' => 0,
                'opening_total_credit' => 0,
                'curr_total_debit' => 0,
                'curr_total_credit' => 0,
                'opening_balance' => 0,
                'opening_balance_side' => 'dr',
                'closing_balance' => $closingStock,
                'closing_balance_side' => 'dr',
            ];

            $stdArr = (object) $arr;

            $mainGroup->subgroupsAccountsForOthers->prepend($stdArr);
        }

        $openingBalanceDebit = isset($mainGroup->opening_total_debit) ? (float) $mainGroup->opening_total_debit : 0;
        $openingBalanceCredit = isset($mainGroup->opening_total_credit) ? (float) $mainGroup->opening_total_credit : 0;

        $CurrTotalDebit = (float) $mainGroup->curr_total_debit;
        $CurrTotalCredit = (float) $mainGroup->curr_total_credit;

        $currOpeningBalance = 0;
        $currOpeningBalanceSide = $defaultBalanceSide;

        if ($openingBalanceDebit > $openingBalanceCredit) {

            $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
            $currOpeningBalanceSide = 'dr';
        } elseif ($openingBalanceCredit > $openingBalanceDebit) {

            $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
            $currOpeningBalanceSide = 'cr';
        }

        $CurrTotalDebit += $closingStock;
        $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
        $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

        $closingBalance = 0;
        $closingBalanceSide = $defaultBalanceSide;
        if ($CurrTotalDebit > $CurrTotalCredit) {

            $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
            $closingBalanceSide = 'dr';
        } elseif ($CurrTotalCredit > $CurrTotalDebit) {

            $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
            $closingBalanceSide = 'cr';
        }

        $mainGroup->opening_balance = $currOpeningBalance;
        $mainGroup->opening_balance_side = $currOpeningBalanceSide;
        $mainGroup->closing_balance = $closingBalance;
        $mainGroup->closing_balance_side = $closingBalanceSide;
    }

    private function mainGroupAccountsBalance($accounts, $defaultBalanceSide)
    {
        foreach ($accounts as $account) {

            $openingBalanceDebit = isset($account->opening_total_debit) ? (float) $account->opening_total_debit : 0;
            $openingBalanceCredit = isset($account->opening_total_credit) ? (float) $account->opening_total_credit : 0;

            $CurrTotalDebit = (float) $account->curr_total_debit;
            $CurrTotalCredit = (float) $account->curr_total_credit;

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = $defaultBalanceSide;

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
            $closingBalanceSide = $defaultBalanceSide;
            if ($CurrTotalDebit > $CurrTotalCredit) {

                $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
                $closingBalanceSide = 'dr';
            } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                $closingBalanceSide = 'cr';
            }

            $account->opening_balance = $currOpeningBalance;
            $account->opening_balance_side = $currOpeningBalanceSide;
            $account->closing_balance = $closingBalance;
            $account->closing_balance_side = $closingBalanceSide;
        }
    }

    private function subGroupsBalance($subGroups, $defaultBalanceSide, $fromDate, $toDate)
    {

        $dates = ['from_date' => $fromDate, 'to_date' => $toDate];
        $request = json_decode(json_encode($dates));

        foreach ($subGroups as $subGroup) {

            if (! isset($subGroup->closing_stock_balance)) {

                $closingStock = 0;
                // if ($subGroup->sub_group_number == 1) {

                //     $profitLossAccountUtil = new \App\Utils\ProfitLossAccountUtil();
                //     $closingStock = $profitLossAccountUtil->closingStock($request)->closing_stock;
                // }

                $openingBalanceDebit = isset($subGroup->opening_total_debit) ? (float) $subGroup->opening_total_debit : 0;
                $openingBalanceCredit = isset($subGroup->opening_total_credit) ? (float) $subGroup->opening_total_credit : 0;

                $CurrTotalDebit = (float) $subGroup->curr_total_debit;
                $CurrTotalCredit = (float) $subGroup->curr_total_credit;

                $currOpeningBalance = 0;
                $currOpeningBalanceSide = $defaultBalanceSide;

                if ($openingBalanceDebit > $openingBalanceCredit) {

                    $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                    $currOpeningBalanceSide = 'dr';
                } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                    $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                    $currOpeningBalanceSide = 'cr';
                }

                $CurrTotalDebit += $closingStock;
                $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
                $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

                $closingBalance = 0;
                $closingBalanceSide = $defaultBalanceSide;
                if ($CurrTotalDebit > $CurrTotalCredit) {

                    $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
                    $closingBalanceSide = 'dr';
                } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                    $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                    $closingBalanceSide = 'cr';
                }

                $subGroup->opening_balance = $currOpeningBalance;
                $subGroup->opening_balance_side = $currOpeningBalanceSide;
                $subGroup->closing_balance = $closingBalance;
                $subGroup->closing_balance_side = $closingBalanceSide;
            }
        }
    }
}
