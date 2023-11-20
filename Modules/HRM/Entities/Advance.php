<?php

namespace Modules\HRM\Entities;

use App\Models\User;

class Advance extends BaseModel
{
    protected $fillable = ['id', 'employee_id', 'permitted_by', 'date', 'amount', 'month', 'year', 'detail'];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\SalaryAdvanceFactory::new();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function permitter()
    {
        return $this->setConnection(config('database.default'))->belongsTo(User::class, 'permitted_by');
    }

    public function getMonthNameAttribute()
    {
        return now()->day(1)->month($this->month)->format('F');
    }
}
