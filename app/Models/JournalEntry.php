<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    // protected $hidden = ['created_at', 'updated_at', 'is_delete_in_update'];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id');
    }

    public function voucherEntryCostCentres()
    {
        return $this->hasMany(VoucherEntryCostCentre::class, 'journal_entry_id');
    }

    public function references()
    {
        return $this->hasMany(PaymentDescriptionReference::class, 'journal_entry_id');
    }

    public function ledger()
    {
        return $this->belongsTo(AccountLedger::class, 'id', 'journal_entry_id');
    }
}
