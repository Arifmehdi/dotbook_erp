<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class LeaveApplicationTableSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = LeaveApplication::count();

        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE leave_applications AUTO_INCREMENT=1');
        }

        LeaveApplication::factory(10)->create();
        // $this->call("OthersTableSeeder");
    }
}
