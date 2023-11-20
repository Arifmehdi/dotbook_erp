<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leads extends BaseModel
{
    use HasFactory;

    protected $table = 'leads_contacts';

    protected $casts = [
        'date_of_birth' => 'date',
        'assigned_to_ids' => 'array',
    ];
}
