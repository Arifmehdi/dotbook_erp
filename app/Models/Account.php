<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id')->select(['id', 'name']);
    }

    public function accountLedgers()
    {
        return $this->hasMany(AccountLedger::class);
    }

    public function accountLedgersWithOutOpeningBalances()
    {
        return $this->hasMany(AccountLedger::class)->where('voucher_type', '!=', 0);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function group()
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function customerOpeningBalances()
    {
        return $this->hasMany(CustomerOpeningBalance::class, 'account_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
