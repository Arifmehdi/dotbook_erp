<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Account::class, 'supplier_account_id');
    }

    public function returnProducts()
    {
        return $this->hasMany(PurchaseReturnProduct::class);
    }

    public function purchaseAccount()
    {
        return $this->belongsTo(Account::class, 'purchase_account_id');
    }
}
