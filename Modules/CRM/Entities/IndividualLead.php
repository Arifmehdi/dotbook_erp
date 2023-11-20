<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndividualLead extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'individual_leads';

    protected $fillable = [
        'name',
        'files',
        'address',
        'companies',
        'description',
        'phone_numbers',
        'email_addresses',
        'additional_information',
    ];

    public function followup_status()
    {
        return $this->setConnection('crm')->hasMany(Followups::class, 'individual_id');
    }

    protected static function newFactory()
    {
        return \Modules\CRM\Database\factories\IndividualLeadFactory::new();
    }
}
