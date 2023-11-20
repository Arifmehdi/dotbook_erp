<?php

namespace Database\Seeders;

use App\Models\Asset\Asset;
use App\Models\Asset\AssetCategory;
use App\Models\Asset\AssetLocation;
use App\Models\Asset\AssetUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        AssetCategory::truncate();
        AssetUnit::truncate();
        AssetLocation::truncate();
        Asset::truncate();

        AssetCategory::factory(10)->create();
        AssetUnit::factory(10)->create();
        AssetLocation::factory(10)->create();
        Asset::factory(20)->create();
    }
}
