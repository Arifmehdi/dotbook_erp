<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryAdjustment extends BaseModel
{
    use HasFactory;

    protected $guarded = [];
    // protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\SalaryAdjustmentFactory::new();
    }

    public function getMonthNameAttribute()
    {
        return now()->day(1)->month($this->month)->format('F');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
