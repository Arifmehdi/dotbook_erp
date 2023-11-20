<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeTaxAdjustment extends BaseModel
{
    use HasFactory;

    protected $table = 'tax_adjustments';

    protected $fillable = ['employee_id', 'type', 'amount', 'month', 'year', 'description'];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\EmployeeTaxAdjustmentFactory::new();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getMonthNameAttribute()
    {
        return now()->day(1)->month($this->month)->format('F');
    }
}
