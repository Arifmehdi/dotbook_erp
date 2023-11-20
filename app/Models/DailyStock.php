<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyStock extends Model
{
    public function dailyStockProducts()
    {
        return $this->hasMany(DailyStockProduct::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
