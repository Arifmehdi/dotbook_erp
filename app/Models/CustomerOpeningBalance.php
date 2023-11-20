<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOpeningBalance extends Model
{
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
