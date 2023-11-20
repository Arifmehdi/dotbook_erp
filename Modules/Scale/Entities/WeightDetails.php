<?php

namespace Modules\Scale\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightDetails extends Model
{
    use HasFactory;

    // protected $guarded = [];
    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Scale\Database\factories\WeightDetailsFactory::new();
    }
}
