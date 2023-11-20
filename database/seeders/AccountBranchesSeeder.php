<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountBranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* `old_erp`.`account_branches` */
        $account_branches = [
            ['id' => '1', 'branch_id' => null, 'account_id' => '1', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '2', 'branch_id' => null, 'account_id' => '2', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '3', 'branch_id' => null, 'account_id' => '3', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '5', 'branch_id' => null, 'account_id' => '4', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '6', 'branch_id' => null, 'account_id' => '5', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '7', 'branch_id' => null, 'account_id' => '6', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '8', 'branch_id' => null, 'account_id' => '7', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '9', 'branch_id' => null, 'account_id' => '8', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '10', 'branch_id' => null, 'account_id' => '9', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '12', 'branch_id' => null, 'account_id' => '10', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '13', 'branch_id' => null, 'account_id' => '11', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '33', 'branch_id' => null, 'account_id' => '12', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '34', 'branch_id' => null, 'account_id' => '13', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '35', 'branch_id' => null, 'account_id' => '14', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '36', 'branch_id' => null, 'account_id' => '15', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '80', 'branch_id' => null, 'account_id' => '16', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '135', 'branch_id' => null, 'account_id' => '17', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '136', 'branch_id' => null, 'account_id' => '18', 'is_delete_in_update' => '0', 'created_at' => null, 'updated_at' => null],
        ];

        \DB::table('account_branches')->insert($account_branches);
    }
}
