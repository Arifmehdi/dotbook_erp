<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TrialBalanceUtil
{
    public function balanceGroupWise($request, $accountStartDate)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        $accountStartDateYmd = '';

        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date);
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $accountGroup = '';
        $query = DB::table('account_groups')->where('account_groups.is_main_group', 0)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'parentGroup.id as parent_group_id',
                'parentGroup.name as parent_group_name',
                'accounts.id as account_id',
                'accountGroup.id as account_group_id',
                'accounts.name as account_name',

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } elseif ($fromDateYmd && $toDateYmd && $fromDateYmd <= $accountStartDateYmd) {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'parentGroup.id as parent_group_id',
                'parentGroup.name as parent_group_name',
                'accounts.id as account_id',
                'accountGroup.id as account_group_id',
                'accounts.name as account_name',

                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type = 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type = 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } else {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'parentGroup.id as parent_group_id',
                'parentGroup.name as parent_group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                'accountGroup.id as account_group_id',

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.debit end), 0) as opening_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.credit end), 0) as opening_total_credit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.debit end), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.credit end), 0) as curr_total_credit'),
            );
        }

        $accountGroup = $query
            ->groupBy('parentGroup.id')
            ->groupBy('account_groups.id')
            // ->groupBy('account_groups.name')
            ->groupBy('account_groups.sub_group_number')
            ->groupBy('account_groups.sub_sub_group_number')
            ->groupBy('accounts.id')
            // ->groupBy('accounts.name')
            ->orderBy('account_groups.sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            [
                'main_group_id' => '',
                'main_group_name' => 'Capital A/c',
                'sub_group_number' => '',
                'sub_sub_group_number' => '',
                'opening_total_debit' => 0,
                'opening_total_credit' => 0,
                'curr_total_debit' => 0,
                'curr_total_credit' => 0,
                'groups' => [],
                'accounts' => [],
            ],
        ];

        return $this->prepareMappedArray($accountGroup, $mappedArray);
    }

    public function prepareMappedArray($results, $mappedArray)
    {
        foreach ($results as $res) {

            $acCurrDebitOpBalance = $res->opening_total_debit;
            $acCurrCreditOpBalance = $res->opening_total_credit;

            $acOpeningBalance = 0;
            $acOpeningBalanceSide = 'dr';
            if ($acCurrDebitOpBalance > $acCurrCreditOpBalance) {

                $acOpeningBalance = $acCurrDebitOpBalance - $acCurrCreditOpBalance;
                $acOpeningBalanceSide = 'dr';
            } elseif ($acCurrCreditOpBalance > $acCurrDebitOpBalance) {

                $acOpeningBalance = $acCurrCreditOpBalance - $acCurrDebitOpBalance;
                $acOpeningBalanceSide = 'cr';
            }

            $acCurrDebit = $res->curr_total_debit + ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
            $acCurrCredit = $res->curr_total_credit + ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);

            $closingBalance = 0;
            $closingBalanceSide = 'dr';
            if ($acCurrDebit > $acCurrCredit) {

                $closingBalance = $acCurrDebit - $acCurrCredit;
                $closingBalanceSide = 'dr';
            } elseif ($acCurrCredit > $acCurrDebit) {

                $closingBalance = $acCurrCredit - $acCurrDebit;
                $closingBalanceSide = 'cr';
            }

            if ($res->sub_sub_group_number == null) {

                $mainArrIndex = null;
                foreach ($mappedArray as $key => $arr) {

                    if ($arr['sub_group_number'] == $res->sub_group_number) {

                        $mainArrIndex = $key;
                    }

                    if (isset($mainArrIndex)) {

                        break;
                    }
                }

                if (! isset($mainArrIndex)) {

                    if ($res->account_id) {

                        $mappedArray[] = [
                            'main_group_id' => $res->group_id,
                            'main_group_name' => $res->group_name,
                            'sub_group_number' => $res->sub_group_number,
                            'sub_sub_group_number' => null,
                            'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                            'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                            'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                            'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                            'groups' => [],
                            'accounts' => [
                                [
                                    'account_id' => $res->account_id,
                                    'account_name' => $res->account_name,
                                    'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                                    'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                                    'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                                    'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                                ],
                            ],
                        ];
                    } else {

                        $mappedArray[] = [
                            'main_group_id' => $res->group_id,
                            'main_group_name' => $res->group_name,
                            'sub_group_number' => $res->sub_group_number,
                            'sub_sub_group_number' => null,
                            'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                            'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                            'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                            'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                            'groups' => [],
                            'accounts' => [],
                        ];
                    }
                } else {

                    if ($res->account_id && $res->parent_group_id != $mappedArray[$mainArrIndex]['main_group_id'] && $res->account_group_id == $mappedArray[$mainArrIndex]['main_group_id']) {

                        array_push($mappedArray[$mainArrIndex]['accounts'], [
                            'account_id' => $res->account_id,
                            'account_name' => $res->account_name,
                            'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                            'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                            'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                            'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                        ]);
                    } else {

                        if ($res->parent_group_id == $mappedArray[$mainArrIndex]['main_group_id']) {

                            $sameSubGroupKey = null;
                            foreach ($mappedArray[$mainArrIndex]['groups'] as $key => $value) {

                                if ($value['group_id'] == $res->group_id) {

                                    $sameSubGroupKey = $key;
                                }

                                if (isset($sameSubGroupKey)) {

                                    break;
                                }
                            }

                            if (!isset($sameSubGroupKey)) {

                                array_push($mappedArray[$mainArrIndex]['groups'], [
                                    'group_id' => $res->group_id,
                                    'group_name' => $res->group_name,
                                    'parent_group_name' => $res->parent_group_name,
                                    'sub_group_number' => $res->sub_group_number,
                                    'sub_sub_group_number' => $res->sub_sub_group_number,
                                    'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                                    'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                                    'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                                    'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                                ]);
                            } else {

                                $mappedArray[$mainArrIndex]['groups'][$sameSubGroupKey]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                                $mappedArray[$mainArrIndex]['groups'][$sameSubGroupKey]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                                $mappedArray[$mainArrIndex]['groups'][$sameSubGroupKey]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                                $mappedArray[$mainArrIndex]['groups'][$sameSubGroupKey]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);
                            }
                        } else {

                            $lastSubGroupKey = count($mappedArray[$mainArrIndex]['groups']) - 1;

                            $mappedArray[$mainArrIndex]['groups'][$lastSubGroupKey]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$lastSubGroupKey]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$lastSubGroupKey]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$lastSubGroupKey]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);
                        }
                    }

                    $mappedArray[$mainArrIndex]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                    $mappedArray[$mainArrIndex]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                    $mappedArray[$mainArrIndex]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                    $mappedArray[$mainArrIndex]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);
                }
            } else {

                if ($closingBalance != 0) {

                    $mainArrIndex = null;
                    foreach ($mappedArray as $key => $arr) {

                        if ($arr['sub_group_number'] == $res->sub_group_number) {

                            $mainArrIndex = $key;
                        }

                        if (isset($mainArrIndex)) {

                            break;
                        }

                        // foreach ($arr['groups'] as $key => $value) {

                        //     if ($value['sub_group_number'] == $res->sub_group_number && $value['sub_sub_group_number'] == $res->sub_sub_group_number) {

                        //         $subGroupArrIndex = $key;
                        //     }
                        // }

                        // if ($mainArrIndex && $subGroupArrIndex) {

                        //     break;
                        // }
                    }

                    if (isset($mainArrIndex)) {

                        $mappedArray[$mainArrIndex]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                        $mappedArray[$mainArrIndex]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                        $mappedArray[$mainArrIndex]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                        $mappedArray[$mainArrIndex]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);

                        $subGroupArrIndex = null;
                        foreach ($mappedArray[$mainArrIndex]['groups'] as $key => $value) {

                            if ($value['sub_group_number'] === $res->sub_group_number && $value['sub_sub_group_number'] === $res->sub_sub_group_number) {

                                $subGroupArrIndex = $key;
                            }

                            if (isset($subGroupArrIndex)) {

                                break;
                            }
                        }

                        if (isset($subGroupArrIndex)) {

                            $mappedArray[$mainArrIndex]['groups'][$subGroupArrIndex]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$subGroupArrIndex]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$subGroupArrIndex]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$subGroupArrIndex]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);
                        } else {

                            array_push($mappedArray[$mainArrIndex]['groups'], [
                                'group_id' => $res->group_id,
                                'group_name' => $res->group_name,
                                'parent_group_name' => $res->parent_group_name,
                                'sub_group_number' => $res->sub_group_number,
                                'sub_sub_group_number' => $res->sub_sub_group_number,
                                'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                                'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                                'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                                'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                            ]);
                        }
                    }
                }
            }
        }

        return $mappedArray;
    }
}
