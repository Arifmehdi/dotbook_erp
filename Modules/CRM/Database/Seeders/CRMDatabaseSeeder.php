<?php

namespace Modules\CRM\Database\Seeders;

use Illuminate\Database\Seeder;

class CRMDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AppointmentsTableSeeder::class);
        $this->call(IndividualLeadTableSeeder::class);
        $this->call(BusinessLeadTableSeeder::class);
    }
}
