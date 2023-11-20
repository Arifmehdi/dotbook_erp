<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $table = 'purchases';

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id')->select(['id', 'warehouse_name', 'warehouse_code', 'phone', 'address']);
    }

    public function purchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id');
    }

    public function orderedProducts()
    {
        return $this->hasMany(PurchaseOrderProduct::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Account::class, 'supplier_account_id');
    }

    public function purchaseAccount()
    {
        return $this->belongsTo(Account::class, 'purchase_account_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function purchaseReturn()
    {
        return $this->hasOne(PurchaseReturn::class, 'purchase_id');
    }

    public function requisition()
    {
        return $this->belongsTo(PurchaseRequisition::class, 'requisition_id');
    }

    public function expense()
    {
        return $this->hasOne(Expanse::class, 'purchase_ref_id');
    }

    public function purchaseByScale()
    {
        return $this->belongsTo(PurchaseByScale::class, 'purchase_by_scale_id');
    }

    public function receiveStock()
    {
        return $this->belongsTo(ReceiveStock::class, 'receive_stock_id');
    }

    public function receiveStocks()
    {
        return $this->hasMany(ReceiveStock::class, 'purchase_order_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'purchase_ref_id');
    }

    public function references()
    {
        return $this->hasMany(PaymentDescriptionReference::class, 'purchase_id');
    }
}
