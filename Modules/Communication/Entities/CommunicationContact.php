<?php

namespace Modules\Communication\Entities;

class CommunicationContact extends BaseModel
{
    protected $table = 'communication_contacts';

    public function group()
    {
        return $this->belongsTo(ContactGroup::class, 'group_id');
    }
}
