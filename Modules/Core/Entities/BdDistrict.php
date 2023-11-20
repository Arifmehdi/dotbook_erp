<?php

namespace Modules\Core\Entities;

class BdDistrict extends BaseModel
{
    protected $table = 'bd_districts';

    protected $fillable = ['id', 'division_id', 'name', 'bn_name', 'lat', 'lon', 'url'];

    public function bdDivision()
    {
        return $this->belongsTo(BdDivision::class, 'division_id', 'id');
    }

    public function bdUpazila()
    {
        return $this->hasMany(BdUpazila::class);
    }
    // public function getDivisionNameAttribute()
    // {
    //     return $this->bdDivision;
    // }
}
