<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function supplier_products()
    {
        return $this->hasMany(SupplierProduct::class)->where('label_qty', '>', 0);
    }

    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class, 'supplier_id');
    }

    public function account()
    {
        return $this->hasOne(Account::class, 'supplier_id');
    }

    public function supplierDetails()
    {
        return $this->hasOne(SupplierDetails::class, 'supplier_id');
    }

    public function supplierContactPersonDetails()
    {
        return $this->hasMany(SupplierContactPersonDetails::class, 'supplier_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
