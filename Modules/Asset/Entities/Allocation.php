<?php

namespace Modules\Asset\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;

    protected $table = 'asset_allocations';

    public function allocated_to_user()
    {
        return $this->belongsTo(User::class, 'allocated_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function revokedBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function revokes()
    {
        return $this->hasMany(Revoke::class);
    }
}
