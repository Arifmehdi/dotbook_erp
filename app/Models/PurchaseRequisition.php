<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function requisitionProducts()
    {
        return $this->hasMany(PurchaseRequisitionProduct::class, 'requisition_id');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(Purchase::class, 'requisition_id')->where('purchase_status', 3);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'requisition_id')->where('purchase_status', 1);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function requester()
    {
        return $this->belongsTo(Requester::class, 'requester_id');
    }
}
