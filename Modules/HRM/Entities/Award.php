<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Award extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'employee_id',
        'award_name',
        'award_description',
        'gift_item',
        'award_by',
        'date',
        'month',
        'year',
    ];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\AwardFactory::new();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
