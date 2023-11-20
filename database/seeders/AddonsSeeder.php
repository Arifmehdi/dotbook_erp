<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AddonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addons = [
            ['id' => '1', 'branches' => '1', 'hrm' => '1', 'todo' => '1', 'service' => '1', 'manufacturing' => '1', 'e_commerce' => '1', 'branch_limit' => '1', 'cash_counter_limit' => '1'],
        ];

        \DB::table('addons')->insert($addons);
    }
}
