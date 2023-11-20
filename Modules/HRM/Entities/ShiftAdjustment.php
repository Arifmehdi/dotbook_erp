<?php

namespace Modules\HRM\Entities;

use Carbon\Carbon;

class ShiftAdjustment extends BaseModel
{
    protected $fillable = [
        'shift_id',
        'start_time',
        'end_time',
        'late_count',
        'applied_date_from',
        'applied_date_to',
        'with_break',
        'break_start',
        'break_end',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function setAppliedDateFromAttribute($value)
    {
        $date = Carbon::parse($value);
        $this->attributes['applied_date_from'] = $date->format('Y-m-d');
    }

    public function setAppliedDateToAttribute($value)
    {
        $date = Carbon::parse($value);
        $this->attributes['applied_date_to'] = $date->format('Y-m-d');
    }

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\ShiftAdjustmentFactory::new();
    }
}
