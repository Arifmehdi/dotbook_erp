<?php

namespace Modules\HRM\Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\Setting;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedOldSettings();
    }

    public function seedOldSettings(): void
    {
        $toInsertArray = [];
        $oldSettings = DB::connection('old')->table('settings')->first();
        $valueArray = \json_decode($oldSettings->general_settings, true);
        foreach ($valueArray as $iKey => $iValue) {
            $toInsertArr1 = [];
            $toInsertArr1['key'] = 'general_settings'.'__'.$iKey;
            $toInsertArr1['value'] = $iValue;
            $toInsertArray[] = $toInsertArr1;
        }
        $valueArray = \json_decode($oldSettings->color_setting, true);
        foreach ($valueArray as $iKey => $iValue) {
            $toInsertArr2 = [];
            $toInsertArr2['key'] = 'color_settings'.'__'.$iKey;
            $toInsertArr2['value'] = $iValue;
            $toInsertArray[] = $toInsertArr2;
        }
        $valueArray = \json_decode($oldSettings->id_card_setting, true);
        foreach ($valueArray as $iKey => $iValue) {
            $toInsertArr3 = [];
            $toInsertArr3['key'] = 'id_card_settings'.'__'.$iKey;
            $toInsertArr3['value'] = $iValue;
            $toInsertArray[] = $toInsertArr3;
        }
        $valueArray = \json_decode($oldSettings->payroll_setting, true);
        foreach ($valueArray as $iKey => $iValue) {
            $toInsertArr4 = [];
            $toInsertArr4['key'] = 'payroll_settings'.'__'.$iKey;
            $toInsertArr4['value'] = $iValue;
            $toInsertArray[] = $toInsertArr4;
        }

        foreach ($toInsertArray as $value) {
            $arr = [
                'key' => $value['key'],
                'value' => $value['value'],
            ];

            Setting::create($value);
        }
    }
}
