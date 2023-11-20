<?php

namespace Modules\CRM\Entities;

use App\Models\Customer;

class ProposalTemplate extends BaseModel
{
    protected $table = 'proposal_templates';

    protected $fillable = [
        'cc', 'bcc', 'proposal_id', 'subject', 'related', 'customer_id', 'lead_id', 'assigned', 'date',
        'open_till', 'currency', 'discount_type', 'tags', 'to', 'address', 'city', 'state', 'country', 'zip', 'phone', 'email', 'comp_des_header', 'comp_des_footer', 'discount', 'sub_total', 'total_item', 'total_qty', 'total', 'taxes', 'body', 'attachments', 'status',
    ];

    public function proposal_details()
    {
        return $this->hasMany(ProposalDetails::class, 'proposal_template_id');
    }

    public function customer()
    {
        return $this->setConnection('mysql')->belongsTo(Customer::class, 'customer_id');
    }

    public function individualLeadsUser()
    {
        return $this->belongsTo(IndividualLead::class, 'lead_id');
    }

    protected static function newFactory()
    {
        return \Modules\CRM\Database\factories\ProposalTemplateFactory::new();
    }
}
