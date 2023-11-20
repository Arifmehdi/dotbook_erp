<?php

namespace Modules\HRM\Entities;

class Section extends BaseModel
{
    protected $fillable = ['hrm_department_id', 'name'];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\SectionFactory::new();
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($section) {
            $section->subSections()->delete();
            return true;
        });
    }

    public function hrmDepartment()
    {
        return $this->belongsTo(HrmDepartment::class);
    }

    public function subSections()
    {
        return $this->hasMany(SubSection::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
