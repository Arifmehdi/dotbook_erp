<?php

namespace Modules\HRM\Entities;

class SubSection extends BaseModel
{
    protected $fillable = ['section_id', 'name'];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\SubSectionFactory::new();
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
