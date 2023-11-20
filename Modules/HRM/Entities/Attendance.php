<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Attendance extends BaseModelWIthoutSoftDelete
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'at_date' => 'datetime: d-m-Y',
        'clock_in_ts' => 'datetime',
        'clock_out_ts' => 'datetime',
        'clock_in' => 'timestamp: H:i',
        'clock_out' => 'timestamp: H:i',
    ];

    protected $fillable = [
        'employee_id',
        'shift_id',
        'clock_in',
        'clock_out',
        'is_manual',
        'status',
        'clock_in_ts',
        'at_date',
        'at_date_ts',
        'clock_out_ts',
        'month',
        'year',
        'bm_clock_in',
        'bm_clock_in_ts',
        'holiday_id',
        'shift',
        'leave_type',
        'manual_entry',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function employeeData()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'id');
    }

    public function getStatusAttribute()
    {
        $status = 'Absent';
        if (isset($this->clock_in)) {
            $status = 'Present';
            $shiftLateCountTime = $this->shift->late_count ?? '0';
            // echo $this->clock_in . '    =   ' . $shiftLateCountTime;
            $isLate = Carbon::parse($this->clock_in)->gt($shiftLateCountTime);
            if ($isLate) {
                $status = 'Late';
            }
        }

        return $status;
    }
}
