<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function descriptions()
    {
        return $this->hasMany(PaymentDescription::class, 'payment_id')->orderBy('created_at', 'asc');
    }

    public function saleReference()
    {
        return $this->belongsTo(Sale::class, 'sale_ref_id');
    }

    public function stockAdjustmentReference()
    {
        return $this->belongsTo(StockAdjustment::class, 'stock_adjustment_ref_id');
    }

    public function purchaseReference()
    {
        return $this->belongsTo(Purchase::class, 'purchase_ref_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function scopeSingleModeDebitDescription($query)
    {
        return $query->where('mode', 1);
    }

    public function singleModeDebitDescription()
    {
        return $this->hasOne(PaymentDescription::class, 'payment_id')->where('amount_type', 'dr');
    }

    public function scopeSingleModeCreditDescriptions($query)
    {
        return $query->where('mode', 1);
    }

    public function singleModeCreditDescriptions()
    {
        return $this->hasMany(PaymentDescription::class, 'payment_id')->where('payment_descriptions.amount_type', 'cr');
    }

    public function scopeSingleModeCreditDescription($query)
    {
        return $query->where('mode', 1);
    }

    public function singleModeCreditDescription()
    {
        return $this->hasOne(PaymentDescription::class, 'payment_id')->where('amount_type', 'cr');
    }

    public function scopeSingleModeDebitDescriptions($query)
    {
        return $query->where('mode', 1);
    }

    public function singleModeDebitDescriptions()
    {
        return $this->hasMany(PaymentDescription::class, 'payment_id')->where('payment_descriptions.amount_type', 'dr');
    }
}
