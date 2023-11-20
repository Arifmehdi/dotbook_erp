<?php

namespace Modules\HRM\Entities;

class LeaveApplication extends BaseModel
{
    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'from_date',
        'to_date',
        'approve_day',
        'attachment',
        'reason',
        'is_paid',
        'status',

    ];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\LeaveApplicationFactory::new();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}
