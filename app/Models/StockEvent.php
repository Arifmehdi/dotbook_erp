<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEvent extends Model
{
    use HasFactory;

    public function stockIssues()
    {
        return $this->hasMany(StockIssue::class, 'stock_event_id');
    }
}
