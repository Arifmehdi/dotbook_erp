<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayBook extends Model
{
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function salesReturn()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id');
    }

    public function contra()
    {
        return $this->belongsTo(Contra::class, 'contra_id');
    }

    public function expense()
    {
        return $this->belongsTo(Expanse::class, 'expense_id');
    }

    public function receiveStock()
    {
        return $this->belongsTo(ReceiveStock::class, 'receive_stock_id');
    }

    public function stockIssue()
    {
        return $this->belongsTo(StockIssue::class, 'stock_issue_id');
    }

    public function dailyStock()
    {
        return $this->belongsTo(DailyStock::class, 'daily_stock_id');
    }

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class, 'stock_adjustment_id');
    }
}
