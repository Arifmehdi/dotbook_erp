<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\HrmDepartment;

class HrmDepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = HrmDepartment::count();

        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE hrm_departments AUTO_INCREMENT=1');
        }

        HrmDepartment::factory(20)->create();
    }
}
