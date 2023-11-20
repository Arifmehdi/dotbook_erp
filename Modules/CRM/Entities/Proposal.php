<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proposal extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [];

    protected $table = 'proposals';

    public function proposalTemplates()
    {
        return $this->belongsTo(ProposalTemplate::class, 'proposal_template_id');
    }

    protected static function newFactory()
    {
        return \Modules\CRM\Database\factories\ProposalFactory::new();
    }
}
