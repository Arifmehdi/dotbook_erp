<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FundFlowUtil
{
    public function capitalAccountBalance($request, $accountStartDate)
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
            ->where('account_groups.sub_group_number', 6)
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
        } else {

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

                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
            );
        }

        $results = $query
            ->groupBy('account_groups.id')
            ->groupBy('account_groups.name')
            ->groupBy('account_groups.sub_group_number')
            ->groupBy('accounts.id')
            ->groupBy('accounts.name')
            ->get();

        // $mappedArray = [
        //     'main_group_id' => 1,
        //     'main_group_name' => 'Capital A/c',
        //     'opening_total_debit' => 0,
        //     'opening_total_credit' => 0,
        //     'curr_total_debit' => 0,
        //     'curr_total_credit' => 0,
        //     'groups' => [],
        //     'accounts' => []
        // ];

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => 'Capital A/c',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'opening_total_debit' => 0,
            'opening_total_credit' => 0,
            'curr_total_debit' => 0,
            'curr_total_credit' => 0,
            'closing_balance' => 0,
            'closing_balance_side' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        $formattedArray = json_decode(json_encode($prepareMappedArray));

        $this->mainGroupClosingBalance($formattedArray, 'cr', 0);
        $this->subGroupClosingBalance($formattedArray, 'cr');
        $this->mainGroupAccountClosingBalance($formattedArray, 'cr');

        return $formattedArray;
    }

    public function loanLiabilitiesAccountBalance($request, $accountStartDate)
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
            ->where('account_groups.sub_group_number', 8)
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
        } else {

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

                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
                // DB::raw("(SUM(account_ledgers.debit) + SUM(account_ledgers.credit)) as all_total"),
            );
        }

        $results = $query
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
            'main_group_id' => '',
            'main_group_name' => 'Capital A/c',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'opening_total_debit' => 0,
            'opening_total_credit' => 0,
            'curr_total_debit' => 0,
            'curr_total_credit' => 0,
            'closing_balance' => 0,
            'closing_balance_side' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        $formattedArray = json_decode(json_encode($prepareMappedArray));

        $this->mainGroupClosingBalance($formattedArray, 'cr', 0);
        $this->subGroupClosingBalance($formattedArray, 'cr');
        $this->mainGroupAccountClosingBalance($formattedArray, 'cr');

        return $formattedArray;
    }

    public function branchAndDivisionsAccountBalance($request, $accountStartDate)
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
            ->where('account_groups.sub_group_number', 5)
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
        } else {

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

                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
            );
        }

        $results = $query
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
            'main_group_id' => '',
            'main_group_name' => 'Capital A/c',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'opening_total_debit' => 0,
            'opening_total_credit' => 0,
            'curr_total_debit' => 0,
            'curr_total_credit' => 0,
            'closing_balance' => 0,
            'closing_balance_side' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        $formattedArray = json_decode(json_encode($prepareMappedArray));

        $this->mainGroupClosingBalance($formattedArray, 'cr', 0);
        $this->subGroupClosingBalance($formattedArray, 'cr');
        $this->mainGroupAccountClosingBalance($formattedArray, 'cr');

        return $formattedArray;
    }

    public function suspenseAccountBalance($request, $accountStartDate)
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
            ->where('account_groups.sub_group_number', 9)
            ->where('accounts.is_main_pl_account', 0)
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
        } else {

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

                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
            );
        }

        $results = $query
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
            'main_group_id' => '',
            'main_group_name' => 'Capital A/c',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'opening_total_debit' => 0,
            'opening_total_credit' => 0,
            'curr_total_debit' => 0,
            'curr_total_credit' => 0,
            'closing_balance' => 0,
            'closing_balance_side' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        $formattedArray = json_decode(json_encode($prepareMappedArray));

        $this->mainGroupClosingBalance($formattedArray, 'cr', 0);
        $this->subGroupClosingBalance($formattedArray, 'cr');
        $this->mainGroupAccountClosingBalance($formattedArray, 'cr');

        return $formattedArray;
    }

    public function netProfitLoss($request, $accountStartDate)
    {
        $profitLossAccountUtil = new \App\Utils\ProfitLossAccountUtil();

        return $profitLossAccountUtil->netProfitLoss($request, $accountStartDate);
    }

    public function currentAssetAccountBalance($request, $accountStartDate)
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
            ->where('account_groups.sub_group_number', 1)
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
        } else {

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

                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
            );
        }

        $results = $query
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
            'main_group_id' => '',
            'main_group_name' => 'Capital A/c',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'opening_total_debit' => 0,
            'opening_total_credit' => 0,
            'curr_total_debit' => 0,
            'curr_total_credit' => 0,
            'closing_balance' => 0,
            'closing_balance_side' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);
        $formattedArray = json_decode(json_encode($prepareMappedArray));

        $profitLossAccountUtil = new \App\Utils\ProfitLossAccountUtil();
        $closingStock = $profitLossAccountUtil->closingStock($request);

        $this->mainGroupClosingBalance($formattedArray, 'dr', $closingStock->closing_stock, $closingStock->opening_stock);

        return $formattedArray;
    }

    public function currentLiabilitiesAccountBalance($request, $accountStartDate)
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
            ->where('account_groups.sub_group_number', 7)
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
        } else {

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

                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
            );
        }

        $results = $query
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
            'main_group_id' => '',
            'main_group_name' => 'Capital A/c',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'opening_total_debit' => 0,
            'opening_total_credit' => 0,
            'curr_total_debit' => 0,
            'curr_total_credit' => 0,
            'closing_balance' => 0,
            'closing_balance_side' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        $formattedArray = json_decode(json_encode($prepareMappedArray));

        $this->mainGroupClosingBalance($formattedArray, 'cr', 0);

        return $formattedArray;
    }

    // public function prepareMappedArray($results, $mappedArray)
    // {
    //     $mainGroupId = '';
    //     foreach ($results as $index => $res) {

    //         if ($index == 0) {

    //             $mainGroupId = $res->group_id;
    //         }

    //         $mappedArray['opening_total_debit'] += isset($res->opening_total_debit) ? (float)$res->opening_total_debit : 0;
    //         $mappedArray['opening_total_credit'] += isset($res->opening_total_credit) ? (float)$res->opening_total_credit : 0;
    //         $mappedArray['curr_total_debit'] += $res->curr_total_debit;
    //         $mappedArray['curr_total_credit'] += $res->curr_total_credit;

    //         if ($mainGroupId == $res->group_id) {

    //             $mappedArray['main_group_id'] = $res->group_id;
    //             $mappedArray['main_group_name'] = $res->group_name;

    //             if ($res->account_name) {
    //                 array_push($mappedArray['accounts'], [
    //                     'account_name' => $res->account_name,
    //                     'opening_total_debit' => isset($res->opening_total_debit) ? (float)$res->opening_total_debit : 0,
    //                     'opening_total_credit' => isset($res->opening_total_credit) ? (float)$res->opening_total_credit : 0,
    //                     'curr_total_debit' => $res->curr_total_debit,
    //                     'curr_total_credit' => $res->curr_total_credit,
    //                 ]);
    //             }
    //         } else {

    //             $getArrayIndex = array_search($res->group_id, array_combine(array_keys($mappedArray['groups']), array_column($mappedArray['groups'], 'group_id')));

    //             if ($getArrayIndex != '') {

    //                 $mappedArray['groups'][$getArrayIndex]['opening_total_debit'] += isset($res->opening_total_debit) ? (float)$res->opening_total_debit : 0;
    //                 $mappedArray['groups'][$getArrayIndex]['opening_total_credit'] += isset($res->opening_total_credit) ? (float)$res->opening_total_credit : 0;
    //                 $mappedArray['groups'][$getArrayIndex]['curr_total_debit'] += (float)$res->curr_total_debit;
    //                 $mappedArray['groups'][$getArrayIndex]['curr_total_credit'] += (float)$res->curr_total_credit;
    //             } else {

    //                 array_push($mappedArray['groups'], [
    //                     'group_id' => $res->group_id,
    //                     'group_name' => $res->group_name,
    //                     'opening_total_debit' => isset($res->opening_total_debit) ? (float)$res->opening_total_debit : 0,
    //                     'opening_total_credit' => isset($res->opening_total_credit) ? (float)$res->opening_total_credit : 0,
    //                     'curr_total_debit' => $res->curr_total_debit,
    //                     'curr_total_credit' => $res->curr_total_credit,
    //                 ]);
    //             }
    //         }
    //     }

    //     return $mappedArray;
    // }

    public function prepareMappedArray($results, $mappedArray)
    {
        foreach ($results as $res) {

            $acCurrDebitOpBalance = isset($res->opening_total_debit) ? $res->opening_total_debit : 0;
            $acCurrCreditOpBalance = isset($res->opening_total_credit) ? $res->opening_total_credit : 0;

            $acCurrDebit = $res->curr_total_debit;
            $acCurrCredit = $res->curr_total_credit;

            if ($res->sub_sub_group_number == null) {

                if ($mappedArray['main_group_id'] == '') {

                    if ($res->account_id) {

                        $mappedArray['main_group_id'] = $res->group_id;
                        $mappedArray['main_group_name'] = $res->group_name;
                        $mappedArray['sub_group_number'] = $res->sub_group_number;
                        $mappedArray['sub_sub_group_number'] = null;
                        $mappedArray['opening_total_debit'] = $acCurrDebitOpBalance;
                        $mappedArray['opening_total_credit'] = $acCurrCreditOpBalance;
                        $mappedArray['curr_total_debit'] = $acCurrDebit;
                        $mappedArray['curr_total_credit'] = $acCurrCredit;

                        array_push($mappedArray['accounts'], [
                            'account_id' => $res->account_id,
                            'account_name' => $res->account_name,
                            'opening_total_debit' => $acCurrDebitOpBalance,
                            'opening_total_credit' => $acCurrCreditOpBalance,
                            'curr_total_debit' => $acCurrDebit,
                            'curr_total_credit' => $acCurrCredit,
                        ]);
                    } else {

                        $mappedArray['main_group_id'] = $res->group_id;
                        $mappedArray['main_group_name'] = $res->group_name;
                        $mappedArray['sub_group_number'] = $res->sub_group_number;
                        $mappedArray['sub_sub_group_number'] = null;
                        $mappedArray['opening_total_debit'] = $acCurrDebitOpBalance;
                        $mappedArray['opening_total_credit'] = $acCurrCreditOpBalance;
                        $mappedArray['curr_total_debit'] = $acCurrDebit;
                        $mappedArray['curr_total_credit'] = $acCurrCredit;
                    }
                } else {

                    if ($res->account_id && $res->parent_group_id != $mappedArray['main_group_id'] && $res->account_group_id == $mappedArray['main_group_id']) {

                        array_push($mappedArray['accounts'], [
                            'account_id' => $res->account_id,
                            'account_name' => $res->account_name,
                            'opening_total_debit' => $acCurrDebitOpBalance,
                            'opening_total_credit' => $acCurrCreditOpBalance,
                            'curr_total_debit' => $acCurrDebit,
                            'curr_total_credit' => $acCurrCredit,
                        ]);
                    } else {

                        if ($res->parent_group_id == $mappedArray['main_group_id']) {

                            $sameSubGroupKey = null;
                            foreach ($mappedArray['groups'] as $key => $value) {

                                if ($value['group_id'] == $res->group_id) {

                                    $sameSubGroupKey = $key;
                                }

                                if (isset($sameSubGroupKey)) {

                                    break;
                                }
                            }

                            if (! isset($sameSubGroupKey)) {

                                array_push($mappedArray['groups'], [
                                    'group_id' => $res->group_id,
                                    'group_name' => $res->group_name,
                                    'parent_group_name' => $res->parent_group_name,
                                    'sub_group_number' => $res->sub_group_number,
                                    'sub_sub_group_number' => $res->sub_sub_group_number,
                                    'opening_total_debit' => $acCurrDebitOpBalance,
                                    'opening_total_credit' => $acCurrCreditOpBalance,
                                    'curr_total_debit' => $acCurrDebit,
                                    'curr_total_credit' => $acCurrCredit,
                                ]);
                            } else {

                                $mappedArray['groups'][$sameSubGroupKey]['opening_total_debit'] += $acCurrDebitOpBalance;
                                $mappedArray['groups'][$sameSubGroupKey]['opening_total_credit'] += $acCurrCreditOpBalance;
                                $mappedArray['groups'][$sameSubGroupKey]['curr_total_debit'] += $acCurrDebit;
                                $mappedArray['groups'][$sameSubGroupKey]['curr_total_credit'] += $acCurrCredit;
                            }
                        } else {

                            $lastSubGroupKey = count($mappedArray['groups']) - 1;

                            $mappedArray['groups'][$lastSubGroupKey]['opening_total_debit'] += $acCurrDebitOpBalance;
                            $mappedArray['groups'][$lastSubGroupKey]['opening_total_credit'] += $acCurrCreditOpBalance;
                            $mappedArray['groups'][$lastSubGroupKey]['curr_total_debit'] += $acCurrDebit;
                            $mappedArray['groups'][$lastSubGroupKey]['curr_total_credit'] += $acCurrCredit;
                        }
                    }

                    $mappedArray['opening_total_debit'] += $acCurrDebitOpBalance;
                    $mappedArray['opening_total_credit'] += $acCurrCreditOpBalance;
                    $mappedArray['curr_total_debit'] += $acCurrDebit;
                    $mappedArray['curr_total_credit'] += $acCurrCredit;
                }
            } else {

                // if ($acCurrDebitOpBalance != 0 || $acCurrCreditOpBalance != 0 || $acCurrDebit != 0 || $acCurrCredit != 0) {

                $mappedArray['opening_total_debit'] += $acCurrDebitOpBalance;
                $mappedArray['opening_total_credit'] += $acCurrCreditOpBalance;
                $mappedArray['curr_total_debit'] += $acCurrDebit;
                $mappedArray['curr_total_credit'] += $acCurrCredit;

                $subGroupArrIndex = null;
                foreach ($mappedArray['groups'] as $key => $value) {

                    if ($value['sub_group_number'] === $res->sub_group_number && $value['sub_sub_group_number'] === $res->sub_sub_group_number) {

                        $subGroupArrIndex = $key;
                    }

                    if (isset($subGroupArrIndex)) {

                        break;
                    }
                }

                if (isset($subGroupArrIndex)) {

                    $mappedArray['groups'][$subGroupArrIndex]['opening_total_debit'] += $acCurrDebitOpBalance;
                    $mappedArray['groups'][$subGroupArrIndex]['opening_total_credit'] += $acCurrCreditOpBalance;
                    $mappedArray['groups'][$subGroupArrIndex]['curr_total_debit'] += $acCurrDebit;
                    $mappedArray['groups'][$subGroupArrIndex]['curr_total_credit'] += $acCurrCredit;
                } else {

                    array_push($mappedArray['groups'], [
                        'group_id' => $res->group_id,
                        'group_name' => $res->group_name,
                        'parent_group_name' => $res->parent_group_name,
                        'sub_group_number' => $res->sub_group_number,
                        'sub_sub_group_number' => $res->sub_sub_group_number,
                        'opening_total_debit' => $acCurrDebitOpBalance,
                        'opening_total_credit' => $acCurrCreditOpBalance,
                        'curr_total_debit' => $acCurrDebit,
                        'curr_total_credit' => $acCurrCredit,
                    ]);
                }
                // }
            }
        }

        return $mappedArray;
    }

    public function mainGroupClosingBalance($arr, $defaultBalanceSide, $closingStock, $openingStock = 0)
    {
        $openingBalanceDebit = isset($arr->opening_total_debit) ? (float) $arr->opening_total_debit : 0;
        $openingBalanceDebit += $openingStock;
        $openingBalanceCredit = isset($arr->opening_total_credit) ? (float) $arr->opening_total_credit : 0;

        $CurrTotalDebit = (float) $arr->curr_total_debit;
        $CurrTotalCredit = (float) $arr->curr_total_credit;

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

        $arr->opening_balance = $currOpeningBalance;
        $arr->opening_balance_side = $currOpeningBalanceSide;
        $arr->closing_balance = $closingBalance;
        $arr->closing_balance_side = $closingBalanceSide;
    }

    public function subGroupClosingBalance($arr, $defaultBalanceSide)
    {
        foreach ($arr->groups as $group) {

            $openingBalanceDebit = isset($group->opening_total_debit) ? (float) $group->opening_total_debit : 0;
            $openingBalanceCredit = isset($group->opening_total_credit) ? (float) $group->opening_total_credit : 0;

            $CurrTotalDebit = (float) $group->curr_total_debit;
            $CurrTotalCredit = (float) $group->curr_total_credit;

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

            $group->closing_balance = $closingBalance;
            $group->closing_balance_side = $closingBalanceSide;
        }
    }

    public function mainGroupAccountClosingBalance($arr, $defaultBalanceSide)
    {
        foreach ($arr->accounts as $account) {

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

            $account->closing_balance = $closingBalance;
            $account->closing_balance_side = $closingBalanceSide;
        }
    }
}
