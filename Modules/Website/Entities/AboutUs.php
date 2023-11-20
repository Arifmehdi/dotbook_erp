<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AboutUs extends BaseModel
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Website\Database\factories\AboutUsFactory::new();
    }
}
