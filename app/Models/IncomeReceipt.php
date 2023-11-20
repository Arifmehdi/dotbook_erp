<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeReceipt extends Model
{
    public function income()
    {
        return $this->belongsTo(Income::class, 'income_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
