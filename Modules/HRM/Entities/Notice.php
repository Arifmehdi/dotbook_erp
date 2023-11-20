<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notice extends BaseModel
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'attachment', 'notice_by', 'is_active'];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\NoticeFactory::new();
    }
}
