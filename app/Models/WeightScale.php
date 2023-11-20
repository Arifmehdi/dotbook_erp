<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightScale extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function firstWeightedBy()
    {
        return $this->belongsTo(User::class, 'first_weighted_by_id');
    }

    public function secondWeightedBy()
    {
        return $this->belongsTo(User::class, 'second_weighted_by_id');
    }

    public function do()
    {
        return $this->belongsTo(Sale::class, 'delivery_order_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
