<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class InterviewQuestions extends BaseModel
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $table = 'interview_questions';
}
