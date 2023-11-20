<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\Grade;

class GradeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();
        $counter = Grade::count();

        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE grades AUTO_INCREMENT = 1');
        }

        Grade::factory(5)->create();
    }
}
