<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostCentre extends Model
{
    public function voucherEntryCostCentres()
    {
        return $this->hasMany(VoucherEntryCostCentre::class, 'cost_centre_id');
    }
}
