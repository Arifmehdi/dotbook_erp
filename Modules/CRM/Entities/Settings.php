<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Settings extends BaseModel
{
    use HasFactory;

    protected $table = 'crm_settings';
}
