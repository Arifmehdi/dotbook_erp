<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\Shift;

class ShiftTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = Shift::count();

        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE shifts AUTO_INCREMENT=1');
        }

        Shift::factory(7)->create();
    }
}
