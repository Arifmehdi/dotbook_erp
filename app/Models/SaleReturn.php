<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function customer()
    {
        return $this->belongsTo(Account::class, 'customer_account_id');
    }

    public function salesAccount()
    {
        return $this->belongsTo(Account::class, 'sale_account_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function sr()
    {
        return $this->belongsTo(User::class, 'sr_user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function returnProducts()
    {
        return $this->hasMany(SaleReturnProduct::class);
    }
}
