<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OvertimeAdjustment extends BaseModel
{
    use HasFactory;

    protected $fillable = ['employee_id', 'type', 'ot_minutes', 'month', 'year', 'description'];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\OvertimeAdjustmentFactory::new();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getMonthNameAttribute()
    {
        return now()->day(1)->month($this->month)->format('F');
    }

    public function getOtMinutesNameAttribute()
    {
        // return now()->day(1)->month($this->month)->format('F');
        // $date->format('Y-m-d')
        $minutes = $this->ot_minutes;

        $hours = floor($minutes / 60);
        $min = $minutes - ($hours * 60);

        return $hours.':'.$min;
    }
}
