<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferStockToBranch extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function Transfer_products()
    {
        return $this->hasMany(TransferStockToBranchProduct::class, 'transfer_stock_id');
    }
}
