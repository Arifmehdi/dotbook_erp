<?php

namespace Modules\Asset\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetServiceConsume extends Model
{
    use HasFactory;

    protected $table = 'asset_service_consumes';

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
