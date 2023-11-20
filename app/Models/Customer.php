<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at'];

    protected $casts = [
        'date_of_birth' => 'date:Y-m-d',
        'assigned_to_ids' => 'array',
    ];

    public function customer_group()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }

    public function customerDetails()
    {

        return $this->hasOne(CustomerDetails::class, 'customer_id');
    }

    public function customerContactPersons()
    {

        return $this->hasMany(CustomerContactPersonDetails::class, 'customer_id');
    }

    public function openingBalance()
    {

        return $this->hasMany(CustomerOpeningBalance::class, 'customer_id');
    }

    public function saleReturns()
    {
        return $this->hasMany(SaleReturn::class, 'customer_id');
    }

    public function receipts()
    {
        return $this->hasMany(MoneyReceipt::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function account()
    {
        return $this->hasOne(Account::class, 'customer_id');
    }

    public function userOpeningBalance()
    {
        return $this->hasOne(CustomerOpeningBalance::class, 'customer_id')->where('user_id', auth()->user()->id);
    }

    public function openingBalances()
    {
        return $this->hasMany(CustomerOpeningBalance::class, 'customer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
