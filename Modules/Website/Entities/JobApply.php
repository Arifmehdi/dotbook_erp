<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobApply extends BaseModel
{
    use HasFactory;

    protected $fillable = [];

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    protected static function newFactory()
    {
        return \Modules\Website\Database\factories\JobApplyFactory::new();
    }

    public function job_applied()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
