<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requester extends Model
{
    use HasFactory;

    protected $table = 'requesters';

    public function requisitions()
    {
        return $this->hasMany(PurchaseRequisition::class, 'requester_id');
    }
}
