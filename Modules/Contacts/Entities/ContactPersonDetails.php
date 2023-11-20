<?php

namespace Modules\Contacts\Entities;

class ContactPersonDetails extends BaseModel
{
    protected $fillable = [
        'contact_id',
        'contact_person_name',
        'contact_person_phon',
        'contact_person_dasignation',
        'contact_person_landline',
        'contact_person_alternative_phone',
        'contact_person_fax',
        'contact_person_email',
        'contact_person_address',
        'contact_person_post_office',
        'contact_person_zip_code',
        'contact_person_police_station',
        'contact_person_state',
        'contact_person_city',
    ];
}
