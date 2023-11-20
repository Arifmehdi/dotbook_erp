<?php

namespace Modules\Scale\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightClient extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory()
    {
        return \Modules\Scale\Database\factories\WeightClientFactory::new();
    }

    public function weightScales()
    {
        return $this->hasMany(Weight::class, 'client_id');
    }
}
