<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Website\Entities\JobApply;

class InterviewSchedule extends BaseModel
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\InterviewScheduleFactory::new();
    }

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }

    public function applicant()
    {
        return $this->setConnection('website')->belongsTo(JobApply::class, 'applicant_id', 'id');
    }
}
