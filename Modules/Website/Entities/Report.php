<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends BaseModel
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Website\Database\factories\ReportFactory::new();
    }
}
