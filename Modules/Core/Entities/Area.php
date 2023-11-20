<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'areas';

    public function realation_Division()
    {
        return $this->belongsTo(BdDivision::class, 'bd_division', 'id');
    }

    public function realation_district()
    {
        return $this->belongsTo(BdDistrict::class, 'bd_district', 'id');
    }

    public function relation_thana()
    {
        return $this->belongsTo(BdThana::class, 'bd_upazilas', 'id');
    }

    public function relation_union()
    {
        return $this->belongsTo(BdUnion::class, 'bd_unions', 'id');
    }

    public function scopeDistricts($query)
    {
        $allDistrictsString = json_decode($this->districts, true);

        return $allDistrictsString;
    }
}
