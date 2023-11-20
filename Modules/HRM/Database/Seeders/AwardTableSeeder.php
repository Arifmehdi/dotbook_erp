<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\Award;

class AwardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = Award::count();

        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE hrm_departments AUTO_INCREMENT=1');
        }

        Award::factory(20)->create();
    }
}
