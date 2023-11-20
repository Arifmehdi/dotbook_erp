<?php

namespace Modules\HRM\Entities;

class HrmDepartment extends BaseModel
{
    protected $fillable = ['name'];
    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\HrmDepartmentFactory::new();
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($department) {
            $department->sections->each(function ($sections) {
                $sections->subSections()->delete();
            });
            $department->sections()->delete();
            return true;
        });
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
