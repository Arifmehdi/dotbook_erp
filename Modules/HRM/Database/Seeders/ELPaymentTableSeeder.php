<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\ELPayment;

class ELPaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = ELPayment::count();

        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE leave_applications AUTO_INCREMENT=1');
        }

        ELPayment::factory(10)->create();
        // $this->call("OthersTableSeeder");
    }
}
