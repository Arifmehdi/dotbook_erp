<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherEntryCostCentre extends Model
{
    protected $table = 'voucher_entry_cost_centres';

    use HasFactory;

    public function costCentre()
    {
        return $this->belongsTo(CostCentre::class, 'cost_centre_id');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function expenseDescription()
    {
        return $this->belongsTo(ExpenseDescription::class, 'expense_description_id');
    }
}
