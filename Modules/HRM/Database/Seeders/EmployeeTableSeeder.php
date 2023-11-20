<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = Employee::count();

        if ($counter == 0) {
            DB::connection('hrm')->statement('ALTER TABLE employees AUTO_INCREMENT = 1');
        }

        Employee::factory(5)->create();
    }
}
