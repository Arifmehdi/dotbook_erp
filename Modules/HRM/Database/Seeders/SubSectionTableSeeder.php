<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\SubSection;

class SubSectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = SubSection::count();

        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE sub_sections AUTO_INCREMENT=1');
        }

        SubSection::factory(10)->create();
    }
}
