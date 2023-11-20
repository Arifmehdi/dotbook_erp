<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends BaseModel
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Website\Database\factories\JobFactory::new();
    }

    public function job_category()
    {
        return $this->belongsTo(JobCategories::class);
    }
}
