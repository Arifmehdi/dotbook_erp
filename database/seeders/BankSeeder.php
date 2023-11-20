<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            ['id' => '1', 'name' => 'BANK 1', 'branch_name' => 'Dhaka Branch', 'address' => 'Dhaka, Bangladesh', 'created_at' => null, 'updated_at' => null],
            ['id' => '2', 'name' => 'BANK 2', 'branch_name' => 'Dhaka Branch', 'address' => 'Dhaka, Bangladesh', 'created_at' => null, 'updated_at' => null],
            ['id' => '3', 'name' => 'BANK 3', 'branch_name' => 'Dhaka Branch', 'address' => 'Dhaka, Bangladesh', 'created_at' => null, 'updated_at' => null],
            ['id' => '4', 'name' => 'BANK 4', 'branch_name' => 'Dhaka Branch', 'address' => 'Dhaka, Bangladesh', 'created_at' => null, 'updated_at' => null],
            ['id' => '5', 'name' => 'BANK 5', 'branch_name' => 'Dhaka Branch', 'address' => 'Dhaka, Bangladesh', 'created_at' => null, 'updated_at' => null],
            ['id' => '6', 'name' => 'BANK 6', 'branch_name' => 'Dhaka Branch', 'address' => 'Dhaka, Bangladesh', 'created_at' => null, 'updated_at' => null],
            ['id' => '7', 'name' => 'BANK 7', 'branch_name' => 'Dhaka Branch', 'address' => 'Dhaka, Bangladesh', 'created_at' => null, 'updated_at' => null],
            ['id' => '8', 'name' => 'BANK 8', 'branch_name' => 'Dhaka Branch', 'address' => 'Dhaka, Bangladesh', 'created_at' => null, 'updated_at' => null],
            ['id' => '9', 'name' => 'Bank 9', 'branch_name' => 'Dhaka', 'address' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '10', 'name' => 'Bank 10', 'branch_name' => 'Dhaka', 'address' => null, 'created_at' => '2022-05-28 07:21:53', 'updated_at' => '2022-05-28 07:21:53'],
        ];

        \DB::table('banks')->insert($banks);
    }
}
