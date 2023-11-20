<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    protected $guarded = [];

    public function methodAccount()
    {
        return $this->hasOne(PaymentMethodSetting::class, 'payment_method_id')->select('payment_method_id', 'account_id');
    }
}
