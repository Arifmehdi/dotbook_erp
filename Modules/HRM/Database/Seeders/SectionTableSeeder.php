<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\Section;

class SectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = Section::count();

        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE sections AUTO_INCREMENT=1');
        }

        Section::factory(10)->create();
    }
}
