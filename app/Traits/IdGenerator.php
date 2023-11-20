<?php

namespace App\Traits;

use App\Models\GeneralSetting;

trait IdGenerator
{
    public function generateCustomerId()
    {
        $lastId = 1;
        $lastEntry = \App\Models\Customer::orderBy('id', 'desc')->first(['id']);
        if (isset($lastEntry->id)) {
            $lastId = $lastEntry->id + 1;
        }
        $generalSettings = GeneralSetting::first();
        $cusIdPrefix = json_decode($generalSettings?->prefix, true)['customer_id'] ?? '';

        return $cusIdPrefix.str_pad($lastId, 4, '0', STR_PAD_LEFT);
    }
}
