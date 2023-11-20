<?php

namespace Modules\Asset\Entities;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetLicenses extends Model
{
    use HasFactory;

    protected $table = 'asset_licenses';

    public function rel_to_asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function rel_to_licenses_category()
    {
        return $this->belongsTo(LicensesCategory::class, 'category_id');
    }

    public function rel_to_supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
