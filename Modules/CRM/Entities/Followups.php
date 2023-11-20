<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Followups extends BaseModel
{
    use HasFactory;

    protected $table = 'followups';

    protected $fillable = ['title', 'individual_id', 'business_id', 'leads_individual_or_business', 'customers_or_leads', 'status', 'date', 'followup_type', 'followup_category', 'assign_to', 'file', 'send_notification', 'description'];

    public function individual_lead()
    {
        return $this->setConnection('crm')->belongsTo(IndividualLead::class, 'individual_id');
    }

    public function busilness_lead()
    {
        return $this->setConnection('crm')->belongsTo(BusinessLead::class, 'business_id');
    }

    public function categories()
    {
        return $this->setConnection('crm')->belongsTo(FollowupCategory::class, 'followup_category');
    }
}
