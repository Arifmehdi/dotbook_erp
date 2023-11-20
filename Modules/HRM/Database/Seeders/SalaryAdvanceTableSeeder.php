<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SalaryAdvanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $count = \Modules\HRM\Entities\Advance::count();
        if ($count == 0) {
            \DB::connection('hrm')->statement('ALTER Table advances AUTO_INCREMENT=1');
        }

        \Modules\HRM\Entities\Advance::factory(10)->create();
        // $this->call("OthersTableSeeder");
    }
}
