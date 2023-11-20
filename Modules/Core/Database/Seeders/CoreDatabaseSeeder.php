<?php

namespace Modules\Core\Database\Seeders;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Core\Entities\BdDistrict;
use Modules\Core\Entities\BdDivision;
use Modules\Core\Entities\BdUnion;
use Modules\Core\Entities\BdUpazila;

class CoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        if (BdDivision::count() == 0) {
            DB::statement('ALTER TABLE bd_divisions AUTO_INCREMENT=1');
        }
        if (BdDistrict::count() == 0) {
            DB::statement('ALTER TABLE bd_districts AUTO_INCREMENT=1');
        }
        if (BdUpazila::count() == 0) {
            DB::statement('ALTER TABLE bd_upazilas AUTO_INCREMENT=1');
        }
        if (BdUnion::count() == 0) {
            DB::statement('ALTER TABLE bd_unions AUTO_INCREMENT=1');
        }

        $this->call(BdDivisionSeeder::class);
        $this->call(BdDistrictSeeder::class);
        $this->call(BdUpazilaSeeder::class);
        $this->call(BdUnionSeeder::class);
    }
}
