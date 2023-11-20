<?php

function searchAccount($keyword)
{
    $accounts = DB::select(DB::raw("
            SELECT 'customers' table_name, id, name, 'account_number' FROM customers WHERE name LIKE '%$keyword%'
            UNION
                SELECT 'suppliers' table_name, id, name, 'account_number' FROM suppliers WHERE name LIKE '%$keyword%'
            UNION
                SELECT
                'accounts' table_name,
                `accounts`.`id`,
                `accounts`.`name`,
                `accounts`.`account_number`
                FROM
                `account_branches`
                LEFT JOIN `accounts` ON `account_branches`.`account_id` = `accounts`.`id`
                WHERE
                `accounts`.`name` LIKE '%$keyword%')
        "));

    return $this->journalUtil->prepareSearchResults($accounts);
}

function test()
{
    $accounts = DB::select(DB::raw("
            SELECT 'customers' table_name, id, name, 'account_number', 'account_type' FROM customers
            UNION
                SELECT 'suppliers' table_name, id, name, 'account_number', 'account_type' FROM suppliers
            UNION
                SELECT
                'accounts' table_name,
                `accounts`.`id`,
                `accounts`.`name`,
                `accounts`.`account_number`,
                `accounts`.`account_type`
                FROM
                `account_branches`
                LEFT JOIN `accounts` ON `account_branches`.`account_id` = `accounts`.`id`
        "));

    $accounts = $this->journalUtil->prepareSearchResults($accounts);

    $users = '';
    if (! auth()->user()->can('view_own_sale')) {

        $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
    }

    return view('accounting.journals.test', compact('accounts', 'users'));
}

function testSearchAccount($keyword)
{
    $__keyword = $keyword == 'NULL' ? '' : $keyword;

    $accounts = DB::select(DB::raw("
            SELECT 'customers' table_name, id, name, 'account_number', 'account_type' FROM customers WHERE name LIKE '%$__keyword%'
            UNION
                SELECT 'suppliers' table_name, id, name, 'account_number', 'account_type' FROM suppliers WHERE name LIKE '%$__keyword%'
            UNION
                SELECT
                'accounts' table_name,
                `accounts`.`id`,
                `accounts`.`name`,
                `accounts`.`account_number`,
                `accounts`.`account_type`
                FROM
                `account_branches`
                LEFT JOIN `accounts` ON `account_branches`.`account_id` = `accounts`.`id`
                WHERE
                `accounts`.`name` LIKE '%$__keyword%'
        "));

    return $this->journalUtil->prepareSearchResults($accounts);
}
