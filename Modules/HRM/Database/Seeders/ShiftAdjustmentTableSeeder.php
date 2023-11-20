<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\ShiftAdjustment;

class ShiftAdjustmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = ShiftAdjustment::count();

        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE shift_adjustments AUTO_INCREMENT=1');
        }

        ShiftAdjustment::factory(10)->create();
    }
}
