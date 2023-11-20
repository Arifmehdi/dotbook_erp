<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\Holiday;

class HolidayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = Holiday::count();

        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE holidays AUTO_INCREMENT=1');
        }

        Holiday::factory(10)->create();
    }
}
