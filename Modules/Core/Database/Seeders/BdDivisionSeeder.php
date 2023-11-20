<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\BdDivision;

class BdDivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        if (! Schema::hasColumn('bd_divisions', 'deleted_at')) {
            Schema::table('bd_divisions', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
        if (BdDivision::count() == 0) {
            $data = $this->getDataArray();
            BdDivision::insert($data);
        }
    }

    public function getDataArray()
    {
        /* `hrm`.`bd_divisions` */
        $bd_divisions = [
            ['id' => '1', 'name' => 'Chattagram', 'bn_name' => 'চট্টগ্রাম', 'url' => 'www.chittagongdiv.gov.bd', 'created_at' => null, 'updated_at' => null],
            ['id' => '2', 'name' => 'Rajshahi', 'bn_name' => 'রাজশাহী', 'url' => 'www.rajshahidiv.gov.bd', 'created_at' => null, 'updated_at' => null],
            ['id' => '3', 'name' => 'Khulna', 'bn_name' => 'খুলনা', 'url' => 'www.khulnadiv.gov.bd', 'created_at' => null, 'updated_at' => null],
            ['id' => '4', 'name' => 'Barisal', 'bn_name' => 'বরিশাল', 'url' => 'www.barisaldiv.gov.bd', 'created_at' => null, 'updated_at' => null],
            ['id' => '5', 'name' => 'Sylhet', 'bn_name' => 'সিলেট', 'url' => 'www.sylhetdiv.gov.bd', 'created_at' => null, 'updated_at' => null],
            ['id' => '6', 'name' => 'Dhaka', 'bn_name' => 'ঢাকা', 'url' => 'www.dhakadiv.gov.bd', 'created_at' => null, 'updated_at' => null],
            ['id' => '7', 'name' => 'Rangpur', 'bn_name' => 'রংপুর', 'url' => 'www.rangpurdiv.gov.bd', 'created_at' => null, 'updated_at' => null],
            ['id' => '8', 'name' => 'Mymensingh', 'bn_name' => 'ময়মনসিংহ', 'url' => 'www.mymensinghdiv.gov.bd', 'created_at' => null, 'updated_at' => null],
        ];

        return $bd_divisions;
    }
}
