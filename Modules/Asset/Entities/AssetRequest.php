<?php

namespace Modules\Asset\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRequest extends Model
{
    use HasFactory;

    protected $table = 'asset_requests';

    public function rel_to_asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function rel_to_user()
    {
        return $this->belongsTo(User::class, 'request_for_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
