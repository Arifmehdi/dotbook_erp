<?php

namespace Modules\Core\Entities;

class BdUpazila extends BaseModel
{
    protected $fillable = ['district_id', 'name', 'bn_name', 'url'];

    protected $table = 'bd_upazilas';

    public function bdDistrict()
    {
        return $this->belongsTo(BdDistrict::class, 'district_id', 'id');
    }

    public function getUpazilaNameAttribute()
    {
        return $this->bdDistrict->name;
    }

    public function bdUnion()
    {
        return $this->hasMany(BdUnion::class);
    }
}
