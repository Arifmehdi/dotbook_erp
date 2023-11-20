<?php

namespace Modules\Core\Entities;

class BdDivision extends BaseModel
{
    protected $fillable = ['id', 'name', 'bn_name', 'url'];

    protected $table = 'bd_divisions';

    // public function
    public function bdDistrict()
    {
        return $this->hasMany(BdDistrict::class);
    }
}
