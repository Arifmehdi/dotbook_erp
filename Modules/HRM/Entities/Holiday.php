<?php

namespace Modules\HRM\Entities;

class Holiday extends BaseModel
{
    protected $fillable = [
        'name',
        'type',
        'from',
        'to',
        'num_of_days',
    ];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\HolidayFactory::new();
    }
}
