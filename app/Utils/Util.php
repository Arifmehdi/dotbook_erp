<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;

class Util
{
    public static function stockAccountingMethods()
    {
        return [
            1 => 'FIFO (FIRST IN - FIRST OUT)',
            2 => 'LIFO (LAST IN - FIRST OUT)',
        ];
    }

    public static function getStockAccountingMethod($index)
    {
        return self::stockAccountingMethods()[$index];
    }

    public static function accountType($index)
    {
        $types = [
            0 => 'N/A',
            1 => 'Cash-In-Hand',
            2 => 'Bank A/c',
            3 => 'Purchase A/c',
            5 => 'Sales A/c',
            7 => 'Direct Expense A/c',
            8 => 'Indirect Expense A/c',
            9 => 'Current Assets A/c',
            10 => 'Current liabilities A/c',
            11 => 'Misc. Expense A/c',
            12 => 'Misc. Income A/c',
            13 => 'Loans & Liabilities A/c',
            14 => 'Loans & Advances A/c',
            15 => 'Fixed Asset A/c',
            16 => 'Investments A/c',
            17 => 'Bank OD A/c',
            18 => 'Deposit A/c',
            19 => 'Provision A/c',
            20 => 'Reserves & Surplus A/c',
            21 => 'Payroll A/c',
            22 => 'Stock Adjustment A/c',
            23 => 'Production A/c',
            24 => 'Direct Income',
            25 => 'Indirect Income',
            26 => 'Capital A/c',
            27 => 'Suspense A/c',
            28 => 'Duties & Taxes A/c',
            29 => 'Secure Loans',
            30 => 'Unsecure Loans',
        ];

        if (array_key_exists($index, $types)) {

            return $types[$index];
        } else {

            return 'Unknown';
        }
    }

    public static function allAccountTypes($forFilter = 0)
    {
        $data = [
            0 => 'N/A',
            1 => 'Cash-In-Hand',
            2 => 'Bank A/c',
            3 => 'Purchase A/c',
            5 => 'Sales A/c',
            7 => 'Direct Expense A/c',
            8 => 'Indirect Expense A/c',
            11 => 'Misc. Expense A/c',
            15 => 'Fixed Asset A/c',
            9 => 'Current Assets A/c',
            24 => 'Direct Income',
            25 => 'Indirect Income',
            12 => 'Misc. Income A/c',
            10 => 'Current liabilities A/c',
            13 => 'Loans & Liabilities A/c',
            14 => 'Loans & Advances A/c',
            16 => 'Investments A/c',
            17 => 'Bank OD A/c',
            18 => 'Deposit A/c',
            19 => 'Provision A/c',
            20 => 'Reserves & Surplus A/c',
            21 => 'Payroll A/c',
            22 => 'Stock Adjustment A/c',
            23 => 'Production A/c',
            26 => 'Capital A/c',
            27 => 'Suspense A/c',
            28 => 'Duties & Taxes A/c',
            29 => 'Secure Loans',
            30 => 'Unsecure Loans',
        ];

        // if ($forFilter == 0) {

        //     if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

        //         return $data;
        //     } else {

        //         return $filteredType =  array_filter($data, function ($val, $key) {
        //             return $key != 2;
        //         }, ARRAY_FILTER_USE_BOTH);
        //     }
        // } else {

        //     return $data;
        // }

        $data = array_filter($data, function ($val) {
            return $val != 'N/A';
        });

        return $data;
    }

    public function addons()
    {
        return DB::table('addons')->first();
    }
}
