<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveStock extends Model
{
    use HasFactory;

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id')->select(['id', 'warehouse_name', 'warehouse_code', 'phone', 'address']);
    }

    public function receiveStockProducts()
    {
        return $this->hasMany(ReceiveStockProduct::class, 'receive_stock_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Account::class, 'supplier_account_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function requisition()
    {
        return $this->belongsTo(PurchaseRequisition::class, 'requisition_id');
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'receive_stock_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(Purchase::class, 'purchase_order_id');
    }
}
