<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProposalDetails extends BaseModel
{
    use HasFactory;

    protected $fillable = ['proposal_template_id', 'item_id', 'name', 'details', 'qty', 'rate', 'tax', 'tax_type', 'discount', 'discount_type', 'amount'];

    protected static function newFactory()
    {
        return \Modules\CRM\Database\factories\ProposalDetailsFactory::new();
    }
}
