<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseDescription extends Model
{
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function expense()
    {
        return $this->belongsTo(Expanse::class, 'expense_id');
    }

    public function voucherEntryCostCentres()
    {
        return $this->hasMany(VoucherEntryCostCentre::class, 'expense_description_id');
    }

    public function ledger()
    {
        return $this->belongsTo(AccountLedger::class, 'id', 'expense_description_id');
    }
}
