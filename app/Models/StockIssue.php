<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIssue extends Model
{
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function event()
    {
        return $this->belongsTo(StockEvent::class, 'stock_event_id');
    }

    public function issueProducts()
    {
        return $this->hasMany(StockIssueProduct::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
