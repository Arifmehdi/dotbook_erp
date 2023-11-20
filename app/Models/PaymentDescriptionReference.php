<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDescriptionReference extends Model
{
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class, 'stock_adjustment_id');
    }

    public function paymentDescription()
    {
        return $this->belongsTo(PaymentDescription::class, 'payment_description_id');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }
}
