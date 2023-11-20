<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\LeaveType;

class LeaveTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = LeaveType::count();

        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE leave_types AUTO_INCREMENT=1');
        }

        LeaveType::factory(5)->create();
    }
}
