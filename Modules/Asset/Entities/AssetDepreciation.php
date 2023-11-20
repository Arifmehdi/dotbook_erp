<?php

namespace Modules\Asset\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDepreciation extends Model
{
    use HasFactory;

    protected $table = 'asset_depreciations';

    public function rel_to_asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id');
    }
}
