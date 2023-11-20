<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointments extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'schedule_date',
        'schedule_time',
        'customer_id',
        'appointor_id',
        'description',
    ];

    protected static function newFactory()
    {
        return \Modules\CRM\Database\factories\AppointmentsFactory::new();
    }
}
