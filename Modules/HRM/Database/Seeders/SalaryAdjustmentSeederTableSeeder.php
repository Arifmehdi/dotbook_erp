<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\SalaryAdjustment;

class SalaryAdjustmentSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $count = SalaryAdjustment::count();

        if ($count != 0) {
            \DB::connection('hrm')->statement('ALTER TABLE salary_adjustments AUTO_INCREMENT = 1');
        }

        // $this->call("OthersTableSeeder");
        SalaryAdjustment::factory(10)->create();
    }
}
