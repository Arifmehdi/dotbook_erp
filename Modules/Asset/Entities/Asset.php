<?php

namespace Modules\Asset\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(AssetUnit::class, 'asset_unit_id');
    }

    public function location()
    {
        return $this->belongsTo(AssetLocation::class, 'location_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function depreciation()
    {
        return $this->hasOne(AssetDepreciation::class);
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function revokes()
    {
        return $this->hasMany(Revoke::class);
    }

    public function supplier()
    {
        return $this->belongsTo(AssetSupplier::class, 'asset_supplier_id');
    }
}
