<?php

namespace Modules\HRM\Entities;

class LeaveType extends BaseModel
{
    protected $fillable = [
        'name',
        'for_months',
        'days',
        'is_active',
    ];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\LeaveTypeFactory::new();
    }

    public function subSections()
    {
        return $this->hasMany(SubSection::class);
    }

    public function leaveApp()
    {
        return $this->hasMany(LeaveApplication::class);
    }
}
