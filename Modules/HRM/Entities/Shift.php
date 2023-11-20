<?php

namespace Modules\HRM\Entities;

class Shift extends BaseModel
{
    protected $casts = [
        'is_allowed_overtime' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'start_time',
        'late_count',
        'end_time',
        'is_allowed_overtime',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\ShiftFactory::new();
    }
}
