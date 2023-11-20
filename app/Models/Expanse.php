<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expanse extends Model
{
    //protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function expenseDescriptions()
    {
        return $this->hasMany(ExpenseDescription::class, 'expense_id');
    }

    public function expensePayments()
    {
        return $this->hasMany(ExpansePayment::class, 'expanse_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_ref_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function scopeSingleModeCreditDescription($query)
    {
        return $query->where('mode', 1);
    }

    public function singleModeCreditDescription()
    {
        return $this->hasOne(ExpenseDescription::class, 'expense_id')->where('amount_type', 'cr');
    }

    public function scopeSingleModeDebitDescriptions($query)
    {
        return $query->where('mode', 1);
    }

    public function singleModeDebitDescriptions()
    {
        return $this->hasMany(ExpenseDescription::class, 'expense_id')->where('expense_descriptions.amount_type', 'dr');
    }

    public function expensePurchase()
    {
        return $this->hasOne(Purchase::class, 'expense_id');
    }
}
