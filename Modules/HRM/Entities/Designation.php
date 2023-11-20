<?php

namespace Modules\HRM\Entities;

class Designation extends BaseModel
{
    protected $fillable = ['section_id', 'name', 'details', 'parent_designation_id'];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\DesignationFactory::new();
    }

    public function sections()
    {
        return $this->hasOne(Section::class, 'id', 'section_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function parent_designation()
    {
        return $this->hasOne(Designation::class, 'id', 'parent_designation_id');
    }

    public function child_designation()
    {
        return $this->hasMany(Designation::class, 'parent_designation_id', 'id');
    }
}
