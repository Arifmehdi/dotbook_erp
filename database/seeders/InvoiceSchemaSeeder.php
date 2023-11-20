<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InvoiceSchemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* `old_erp`.`invoice_schemas` */
        $invoice_schemas = [
            ['id' => '3', 'name' => 'yyyy', 'format' => '1', 'start_from' => '11', 'number_of_digit' => null, 'is_default' => '0', 'prefix' => 'SDC0', 'created_at' => '2021-03-02 08:07:36', 'updated_at' => '2021-06-06 12:05:25'],
            ['id' => '6', 'name' => 'sss', 'format' => '1', 'start_from' => '00', 'number_of_digit' => null, 'is_default' => '0', 'prefix' => 'SD', 'created_at' => '2021-03-02 08:56:49', 'updated_at' => '2022-05-27 10:39:29'],
            ['id' => '9', 'name' => 'test', 'format' => '1', 'start_from' => null, 'number_of_digit' => null, 'is_default' => '1', 'prefix' => 'MC', 'created_at' => '2021-06-06 12:02:32', 'updated_at' => '2022-05-27 10:39:29'],
            ['id' => '12', 'name' => 'TEST-4', 'format' => '2', 'start_from' => '12', 'number_of_digit' => null, 'is_default' => '0', 'prefix' => '2021/', 'created_at' => '2021-08-16 11:08:29', 'updated_at' => '2021-08-16 11:08:29'],
        ];
        \DB::table('invoice_schemas')->insert($invoice_schemas);

    }
}
