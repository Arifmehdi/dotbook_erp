<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends BaseModel
{
    use HasFactory;

    protected $fillable = [];

    protected $table = 'crm_tasks';

    protected static function newFactory()
    {
        return \Modules\CRM\Database\factories\TaskFactory::new();
    }
}
