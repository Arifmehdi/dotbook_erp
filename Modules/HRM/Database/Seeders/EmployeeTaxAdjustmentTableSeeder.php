<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\EmployeeTaxAdjustment;

class EmployeeTaxAdjustmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $count = EmployeeTaxAdjustment::count();
        if ($count > 0) {
            \DB::connection('hrm')->statement('ALTER TABLE tax_adjustments AUTO_INCREMENT = 1');
        }
        EmployeeTaxAdjustment::factory(5)->create();
        // $this->call("OthersTableSeeder");
    }
}
