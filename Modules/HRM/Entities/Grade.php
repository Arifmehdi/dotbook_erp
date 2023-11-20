<?php

namespace Modules\HRM\Entities;

use DB;

class Grade extends BaseModel
{
    protected $fillable = [
        'name',
        'basic',
        'house_rent',
        'medical',
        'food',
        'transport',
        'other',
    ];

    public function getGrossSalaryAttribute()
    {
        return $this->basic + $this->house_rent + $this->medical + $this->food + $this->transport + $this->other;
    }

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\GradeFactory::new();
    }
}
