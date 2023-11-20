<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseByScale extends Model
{
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Account::class, 'supplier_account_id');
    }

    public function weights()
    {
        return $this->hasMany(PurchaseByScaleWeight::class);
    }

    public function weightsByProduct()
    {
        return $this->hasMany(PurchaseByScaleWeight::class)->where('is_first_weight', 0);
    }
}
