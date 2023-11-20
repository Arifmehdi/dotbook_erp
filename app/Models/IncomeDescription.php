<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeDescription extends Model
{
    use HasFactory;

    public function account()
    {
        return $this->belongsTo(Account::class, 'income_account_id', 'id');
    }
}
