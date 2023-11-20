<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIssueProduct extends Model
{
    public function stockIssue()
    {
        return $this->belongsTo(StockIssue::class, 'stock_issue_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function issueUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
