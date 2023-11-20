<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class, 'sale_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'sale_ref_id');
    }

    public function references()
    {
        return $this->hasMany(PaymentDescriptionReference::class, 'sale_id');
    }

    public function customer()
    {
        return $this->belongsTo(Account::class, 'customer_account_id');
    }

    public function salesAccount()
    {
        return $this->belongsTo(Account::class, 'sale_account_id');
    }

    public function salesTaxAccount()
    {
        return $this->belongsTo(Account::class, 'tax_ac_id');
    }

    public function return()
    {
        return $this->hasOne(SaleReturn::class, 'sale_id');
    }

    public function saleBy()
    {
        return $this->belongsTo(User::class, 'sale_by_id');
    }

    public function doBy()
    {
        return $this->belongsTo(User::class, 'do_by_id');
    }

    public function orderBy()
    {
        return $this->belongsTo(User::class, 'order_by_id');
    }

    public function quotationBy()
    {
        return $this->belongsTo(User::class, 'quotation_by_id');
    }

    public function sr()
    {
        return $this->belongsTo(User::class, 'sr_user_id');
    }

    public function gatePass()
    {
        return $this->hasOne(GatePass::class);
    }

    public function weight()
    {
        return $this->hasOne(WeightScale::class, 'sale_id');
    }

    public function lastWeight()
    {
        return $this->hasOne(WeightScale::class);
    }

    public function do()
    {
        return $this->belongsTo(Sale::class, 'delivery_order_id');
    }

    public function salePayments()
    {
        return $this->hasMany(SalePayment::class, 'sale_id')->where('customer_payment_id', null);
    }
}
