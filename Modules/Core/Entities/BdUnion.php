<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BdUnion extends BaseModel
{
    use HasFactory;

    protected $fillable = ['id', 'upazilla_id', 'name', 'bn_name', 'url'];

    protected $table = 'bd_unions';

    public function bdUpazila()
    {
        return $this->belongsTo(BdUpazila::class, 'upazilla_id', 'id');
    }
}
