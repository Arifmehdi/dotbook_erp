<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\OvertimeAdjustment;

class OvertimeAdjustmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        $count = OvertimeAdjustment::count();
        if ($count > 0) {
            \DB::connection('hrm')->statement('ALTER TABLE overtime_adjustments AUTO_INCREMENT = 1');
        }
        OvertimeAdjustment::factory(5)->create();
    }
}
