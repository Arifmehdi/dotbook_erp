<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'promotions';

    protected $guarded = [];

    public function hrmDepartment()
    {
        return $this->hasOne(HrmDepartment::class, 'id', 'hrm_department_id');
    }

    public function section()
    {
        return $this->hasOne(Section::class, 'id', 'section_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function approver()
    {
        return $this->hasOne(Employee::class, 'id', 'user_id');
    }

    public function designation()
    {
        return $this->hasOne(Designation::class, 'id', 'designation_id');
    }

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\PromotionFactory::new();
    }
}
