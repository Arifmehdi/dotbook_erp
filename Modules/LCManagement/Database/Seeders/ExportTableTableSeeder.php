<?php

namespace Modules\LCManagement\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\LCManagement\Entities\Exporter;

class ExportTableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Exporter::Factory()->create(10);
        // $this->call("OthersTableSeeder");
    }
}
