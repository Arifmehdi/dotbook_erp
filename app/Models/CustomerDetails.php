<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDetails extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function customer_info()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
