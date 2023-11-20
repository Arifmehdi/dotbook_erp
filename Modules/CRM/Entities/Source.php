<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Source extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'description'];
}
