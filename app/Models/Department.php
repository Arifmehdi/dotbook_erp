<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function requisitions()
    {
        return $this->hasMany(PurchaseRequisition::class);
    }

    public function stockIssues()
    {
        return $this->hasMany(StockIssue::class);
    }
}
