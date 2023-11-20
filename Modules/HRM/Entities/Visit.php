<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visit extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'from_date',
        'to_date',
        'category',
        'attachments',
        'description',
    ];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\VisitFactory::new();
    }
}
