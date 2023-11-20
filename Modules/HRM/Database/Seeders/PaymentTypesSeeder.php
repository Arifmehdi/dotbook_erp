<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\PaymentType;

class PaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $counter = PaymentType::count();
        if ($counter == 0) {
            \DB::connection('hrm')->statement('ALTER TABLE payment_types AUTO_INCREMENT=1');
        }

        // $this->call("OthersTableSeeder");
        PaymentType::factory(10)->create();
    }
}
