<?php

namespace Modules\LCManagement\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\LCManagement\Entities\AdvisingBank;

class AdvisingbankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        AdvisingBank::Factory()->create(10);
        // $this->call("OthersTableSeeder");
    }
}
