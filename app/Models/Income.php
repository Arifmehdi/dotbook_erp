<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function incomeDescriptions()
    {
        return $this->hasMany(IncomeDescription::class, 'income_id');
    }

    public function incomeReceipts()
    {
        return $this->hasMany(IncomeReceipt::class, 'income_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
