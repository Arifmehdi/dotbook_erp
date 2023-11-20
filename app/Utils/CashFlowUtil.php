<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CashFlowUtil
{
    public function capitalAccountCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 6)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function branchAndDivisionCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 5)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function currentLiabilitiesCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 7)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function loanLiabilitiesCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 8)

            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_groups.sub_sub_group_number != 11 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_groups.sub_sub_group_number != 11 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_groups.sub_sub_group_number != 11 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_groups.sub_sub_group_number != 11 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function currentAssetsCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 1)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_groups.sub_sub_group_number != 1 and account_groups.sub_sub_group_number != 2 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_groups.sub_sub_group_number != 1 and account_groups.sub_sub_group_number != 2 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_groups.sub_sub_group_number != 1 and account_groups.sub_sub_group_number != 2 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_groups.sub_sub_group_number != 1 and account_groups.sub_sub_group_number != 2 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function fixedAssetsCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 2)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function investmentsCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 3)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function directExpenseCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 10)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function indirectExpenseCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 11)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function purchaseCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 12)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function directIncomeCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 13)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function indirectIncomeCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 14)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function salesAccountCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 15)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    public function suspenseAccountCashFlows($request)
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.is_main_group', 0)
            ->where('account_groups.sub_group_number', 9)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in"),
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

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.credit end), 0) as cash_in'),
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
            ->orderBy('account_groups.sub_sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => '',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    // public function prepareMappedArray($results, $mappedArray)
    // {
    //     $mainGroupId = '';
    //     foreach ($results as $index => $res) {

    //         if ($index == 0) {

    //             $mainGroupId = $res->group_id;
    //         }

    //         $mappedArray['cash_in'] += $res->cash_in;
    //         $mappedArray['cash_out'] += $res->cash_out;

    //         if ($mainGroupId == $res->group_id) {

    //             $mappedArray['main_group_id'] = $res->group_id;
    //             $mappedArray['main_group_name'] = $res->group_name;

    //             if ($res->account_name) {

    //                 array_push($mappedArray['accounts'], [
    //                     'account_id' => $res->account_id,
    //                     'account_name' => $res->account_name,
    //                     'cash_in' => $res->cash_in,
    //                     'cash_out' => $res->cash_out,
    //                 ]);
    //             }
    //         } else {

    //             $getArrayIndex = array_search($res->group_id, array_combine(array_keys($mappedArray['groups']), array_column($mappedArray['groups'], 'group_id')));

    //             $getArraySubGroupNumber = array_search($res->sub_group_number, array_combine(array_keys($mappedArray['groups']), array_column($mappedArray['groups'], 'sub_group_number')));

    //             if ($getArrayIndex != '' && $getArraySubGroupNumber != '') {

    //                 $mappedArray['groups'][$getArrayIndex]['cash_in'] += $res->cash_in;
    //                 $mappedArray['groups'][$getArrayIndex]['cash_out'] += $res->cash_out;
    //             } else {

    //                 array_push($mappedArray['groups'], [
    //                     'group_id' => $res->group_id,
    //                     'group_name' => $res->group_name,
    //                     'parent_group_name' => isset($res->parent_group_name) ? $res->parent_group_name : '',
    //                     'sub_group_number' => isset($res->sub_group_number) ? $res->sub_group_number : null,
    //                     'sub_sub_group_number' => isset($res->sub_sub_group_number) ? $res->sub_sub_group_number : null,
    //                     'cash_in' => $res->cash_in,
    //                     'cash_out' => $res->cash_out,
    //                 ]);
    //             }
    //         }
    //     }

    //     return $mappedArray;
    // }

    public function prepareMappedArray($results, $mappedArray)
    {
        foreach ($results as $res) {

            $cashIn = $res->cash_in;
            $cashOut = $res->cash_out;

            if ($res->sub_sub_group_number == null) {

                if ($mappedArray['main_group_id'] == '') {

                    if ($res->account_id) {

                        $mappedArray['main_group_id'] = $res->group_id;
                        $mappedArray['main_group_name'] = $res->group_name;
                        $mappedArray['sub_group_number'] = $res->sub_group_number;
                        $mappedArray['sub_sub_group_number'] = null;
                        $mappedArray['cash_in'] = $cashIn;
                        $mappedArray['cash_out'] = $cashOut;

                        array_push($mappedArray['accounts'], [
                            'account_id' => $res->account_id,
                            'account_name' => $res->account_name,
                            'cash_in' => $cashIn,
                            'cash_out' => $cashOut,
                        ]);
                    } else {

                        $mappedArray['main_group_id'] = $res->group_id;
                        $mappedArray['main_group_name'] = $res->group_name;
                        $mappedArray['sub_group_number'] = $res->sub_group_number;
                        $mappedArray['sub_sub_group_number'] = null;
                        $mappedArray['cash_in'] = $cashIn;
                        $mappedArray['cash_out'] = $cashOut;
                    }
                } else {
                    if ($res->account_id && $res->parent_group_id != $mappedArray['main_group_id'] && $res->account_group_id == $mappedArray['main_group_id']) {

                        array_push($mappedArray['accounts'], [
                            'account_id' => $res->account_id,
                            'account_name' => $res->account_name,
                            'cash_in' => $cashIn,
                            'cash_out' => $cashOut,
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
                                    'cash_in' => $cashIn,
                                    'cash_out' => $cashOut,
                                ]);
                            } else {

                                $mappedArray['groups'][$sameSubGroupKey]['cash_in'] += $cashIn;
                                $mappedArray['groups'][$sameSubGroupKey]['cash_out'] += $cashOut;
                            }
                        } else {

                            $lastSubGroupKey = count($mappedArray['groups']) - 1;

                            $mappedArray['groups'][$lastSubGroupKey]['cash_in'] += $cashIn;
                            $mappedArray['groups'][$lastSubGroupKey]['cash_out'] += $cashOut;
                        }
                    }

                    $mappedArray['cash_in'] += $cashIn;
                    $mappedArray['cash_out'] += $cashOut;
                }
            } else {

                $mappedArray['cash_in'] += $cashIn;
                $mappedArray['cash_out'] += $cashOut;

                $subGroupArrIndex = null;
                foreach ($mappedArray['groups'] as $key => $value) {

                    if ($value['sub_group_number'] === $res->sub_group_number && $value['sub_sub_group_number'] === $res->sub_sub_group_number) {

                        $subGroupArrIndex = $key;
                    }

                    if ($subGroupArrIndex != null) {

                        break;
                    }
                }

                if (isset($subGroupArrIndex)) {

                    $mappedArray['groups'][$subGroupArrIndex]['cash_in'] += $cashIn;
                    $mappedArray['groups'][$subGroupArrIndex]['cash_out'] += $cashOut;
                } else {

                    array_push($mappedArray['groups'], [
                        'group_id' => $res->group_id,
                        'group_name' => $res->group_name,
                        'parent_group_name' => $res->parent_group_name,
                        'sub_group_number' => $res->sub_group_number,
                        'sub_sub_group_number' => $res->sub_sub_group_number,
                        'cash_in' => $cashIn,
                        'cash_out' => $cashOut,
                    ]);
                }
            }
        }

        return $mappedArray;
    }

    private function checkIsCashOrNot($query)
    {
        $query->leftJoin('journal_entries', 'account_ledgers.journal_entry_id', 'journal_entries.id');
        $query->leftJoin('journals', 'journal_entries.journal_id', 'journals.id');
        $query->leftJoin('journal_entries as journalEntries', 'journals.id', 'journalEntries.journal_id');
        $query->leftJoin('accounts as journalEntriesAccount', 'journalEntries.account_id', 'journalEntriesAccount.id');
        $query->leftJoin('account_groups as journalEntriesAccountGroup', 'journalEntriesAccount.account_group_id', 'journalEntriesAccountGroup.id');

        $query->leftJoin('payment_descriptions', 'account_ledgers.payment_description_id', 'payment_descriptions.id');
        $query->leftJoin('payments', 'payment_descriptions.payment_id', 'payments.id');
        $query->leftJoin('payment_descriptions as paymentDescriptions', 'payments.id', 'paymentDescriptions.payment_id');
        $query->leftJoin('accounts as paymentDescriptionsAccount', 'paymentDescriptions.account_id', 'paymentDescriptionsAccount.id');
        $query->leftJoin('account_groups as paymentDescriptionsAccountGroup', 'paymentDescriptionsAccount.account_group_id', 'paymentDescriptionsAccountGroup.id');

        // $query->where('journalEntriesAccountGroup.sub_sub_group_number', 1);
        // $query->orWhere('paymentDescriptionsAccountGroup.sub_sub_group_number', 1);
        // $query->orWhere('journalEntriesAccountGroup.sub_sub_group_number', 2);
        // $query->orWhere('journalEntriesAccountGroup.sub_sub_group_number', 11);
        // $query->orWhere('paymentDescriptionsAccountGroup.sub_sub_group_number', 2);
        // $query->orWhere('paymentDescriptionsAccountGroup.sub_sub_group_number', 11);

        return $query;
    }

    public function groupCashFlowDetails($groupId, $groupLevel, $fromDate, $toDate)
    {
        $group = DB::table('account_groups')->where('id', $groupId)->first();

        $fromDateYmd = '';
        $toDateYmd = '';
        if ($fromDate && $toDate) {

            $fromDateYmd = Carbon::parse($fromDate)->startOfDay();
            $toDateYmd = Carbon::parse($toDate)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.is_main_group', 0);

        if ($groupLevel == 'sub_group_number') {

            $query->where('account_groups.'.$groupLevel, $group->{$groupLevel});
        } else {

            $query->where('account_groups.id', $group->id);
        }

        $query->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->where('account_ledgers.is_cash_flow', 1);

        if ($fromDateYmd && $toDateYmd) {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'account_groups.main_group_number',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'parentGroup.name as parent_group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.voucher_type != 1 and account_ledgers.voucher_type != 2 and account_ledgers.voucher_type != 3 and account_ledgers.voucher_type != 4 then account_ledgers.debit end), 0) as cash_out"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type != 0 and account_ledgers.voucher_type != 1 and account_ledgers.voucher_type != 2 and account_ledgers.voucher_type != 3 and account_ledgers.voucher_type != 4 then account_ledgers.credit end), 0) as cash_in"),
            );
        } else {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'account_groups.main_group_number',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'parentGroup.name as parent_group_name',
                'accounts.id as account_id',
                'accounts.name as account_name',
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.voucher_type != 1 and account_ledgers.voucher_type != 2 and account_ledgers.voucher_type != 3 and account_ledgers.voucher_type != 4 then account_ledgers.debit end), 0) as cash_out'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 and account_ledgers.voucher_type != 1 and account_ledgers.voucher_type != 2 and account_ledgers.voucher_type != 3 and account_ledgers.voucher_type != 4 then account_ledgers.credit end), 0) as cash_in'),
            );
        }

        $results = $query
            ->groupBy('account_groups.id')
            ->groupBy('account_groups.name')
            ->groupBy('account_groups.main_group_number')
            ->groupBy('account_groups.sub_group_number')
            ->groupBy('account_groups.sub_sub_group_number')
            ->groupBy('parentGroup.name')
            // ->groupBy('account_groups.' . $groupLevel)
            ->groupBy('accounts.id')
            ->groupBy('accounts.name')
            ->get();

        $mappedArray = [
            'main_group_id' => 1,
            'main_group_name' => 'Capital A/c',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }
}
