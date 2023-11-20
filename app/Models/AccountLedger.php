<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountLedger extends Model
{
    use HasFactory;

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contraDescription()
    {
        return $this->belongsTo(ContraDescription::class, 'contra_description_id');
    }

    public function paymentDescription()
    {
        return $this->belongsTo(PaymentDescription::class, 'payment_description_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function saleProduct()
    {
        return $this->belongsTo(SaleProduct::class, 'sale_product_id');
    }

    public function salesReturn()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id');
    }

    public function salesReturnProduct()
    {
        return $this->belongsTo(SaleReturnProduct::class, 'sale_return_product_id');
    }

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class, 'adjustment_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function purchaseProduct()
    {
        return $this->belongsTo(PurchaseProduct::class, 'purchase_product_id');
    }

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    public function purchaseReturnProduct()
    {
        return $this->belongsTo(PurchaseReturnProduct::class, 'purchase_return_product_id');
    }

    public function expenseDescription()
    {
        return $this->belongsTo(ExpenseDescription::class, 'expense_description_id');
    }
}
