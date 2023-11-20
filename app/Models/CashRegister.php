<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function cash_register_transactions()
    {
        return $this->hasMany(CashRegisterTransaction::class);
    }

    public function cash_counter()
    {
        return $this->belongsTo(CashCounter::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
