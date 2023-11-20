<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    public function entries()
    {
        return $this->hasMany(JournalEntry::class, 'journal_id');
    }

    public function debitEntries()
    {
        return $this->hasMany(JournalEntry::class, 'journal_id')->where('amount_type', 'debit');
    }

    public function creditEntry()
    {
        return $this->hasOne(JournalEntry::class, 'journal_id')->where('amount_type', 'credit');
    }

    public function creditEntries()
    {
        return $this->hasMany(JournalEntry::class, 'journal_id')->where('amount_type', 'credit');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
