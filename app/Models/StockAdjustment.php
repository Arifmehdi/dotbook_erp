<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function adjustmentProducts()
    {
        return $this->hasMany(StockAdjustmentProduct::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'expense_account_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'stock_adjustment_ref_id');
    }

    public function references()
    {
        return $this->hasMany(PaymentDescriptionReference::class, 'stock_adjustment_id');
    }
}
