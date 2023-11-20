<?php

namespace Modules\Asset\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Asset\Entities\AssetUnit;

class SeedAssetUnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        AssetUnit::factory()->create(10);

        // $this->call("OthersTableSeeder");
    }
}
