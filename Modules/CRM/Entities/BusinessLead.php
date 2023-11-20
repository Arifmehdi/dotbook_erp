<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessLead extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'business_leads';

    protected $fillable = [
        'name',
        'location',
        'phone_numbers',
        'email_addresses',
        'total_employees',
        'description',
        'additional_information',
        'files',
    ];

    protected static function newFactory()
    {
        return \Modules\CRM\Database\factories\BusinessLeadFactory::new();
    }

    public function followup_status()
    {
        return $this->setConnection('crm')->hasMany(Followups::class, 'business_id');
    }
}
