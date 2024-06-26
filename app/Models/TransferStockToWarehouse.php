<?php

namespace App\Models;

// use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TransferStockToWarehouse extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    // public function admin()
    // {
    //     return $this->belongsTo(User::class, 'admin_id');
    // }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function Transfer_products()
    {
        return $this->hasMany(TransferStockToWarehouseProduct::class, 'transfer_stock_id');
    }
}
