<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProposalComment extends BaseModel
{
    use HasFactory;

    protected $fillable = ['comments'];

    protected static function newFactory()
    {
        return \Modules\CRM\Database\factories\ProposalCommentFactory::new();
    }
}
