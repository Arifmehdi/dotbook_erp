<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalarySettlement extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'amount_type',
        'salary_type',
        'previous',
        'how_much_amount',
        'after_updated',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\SalarySettlementFactory::new();
    }
}
