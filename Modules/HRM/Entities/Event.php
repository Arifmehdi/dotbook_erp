<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start',
        'end',
        'color',
    ];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\EventFactory::new();
    }
}
