<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\Designation;

class DesignationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = Designation::count();

        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE designations AUTO_INCREMENT=1');
        }

        Designation::factory(10)->create();
    }
}
