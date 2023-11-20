<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contra extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function senderAccount()
    {
        return $this->belongsTo(Account::class, 'sender_account_id');
    }

    public function receiverAccount()
    {
        return $this->belongsTo(Account::class, 'receiver_account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeSingleModeDebitDescription($query)
    {
        return $query->where('mode', 1);
    }

    public function singleModeDebitDescription()
    {
        return $this->hasOne(ContraDescription::class, 'contra_id')->where('amount_type', 'dr');
    }

    public function scopeSingleModeCreditDescriptions($query)
    {
        return $query->where('mode', 1);
    }

    public function singleModeCreditDescriptions()
    {
        return $this->hasMany(ContraDescription::class, 'contra_id')->where('contra_descriptions.amount_type', 'cr');
    }

    public function descriptions()
    {
        return $this->hasMany(ContraDescription::class, 'contra_id');
    }
}
