<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* `old_erp`.`payment_methods` */
        $payment_methods = [
            ['id' => '3', 'name' => 'Cash', 'is_fixed' => '1', 'created_at' => null, 'updated_at' => '2022-01-06 08:11:04'],
            ['id' => '4', 'name' => 'Debit-Card', 'is_fixed' => '1', 'created_at' => null, 'updated_at' => null],
            ['id' => '5', 'name' => 'Credit-Card', 'is_fixed' => '1', 'created_at' => null, 'updated_at' => null],
            ['id' => '7', 'name' => 'Bank-Transfer', 'is_fixed' => '1', 'created_at' => null, 'updated_at' => null],
            ['id' => '8', 'name' => 'Cheque', 'is_fixed' => '1', 'created_at' => null, 'updated_at' => null],
            ['id' => '9', 'name' => 'American Express', 'is_fixed' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '10', 'name' => 'Bkash', 'is_fixed' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '11', 'name' => 'Rocket', 'is_fixed' => '0', 'created_at' => null, 'updated_at' => null],
            ['id' => '12', 'name' => 'Nagad', 'is_fixed' => '0', 'created_at' => null, 'updated_at' => null],
        ];
        \DB::table('payment_methods')->insert($payment_methods);
    }
}
