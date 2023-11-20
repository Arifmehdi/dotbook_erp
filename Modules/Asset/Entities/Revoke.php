<?php

namespace Modules\Asset\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revoke extends Model
{
    use HasFactory;

    protected $table = 'asset_revokes';

    public function allocation()
    {
        return $this->belongsTo(Allocation::class, 'allocation_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function revokedBy()
    {
        return $this->belongsTo(User::class, 'revoke_by_id');
    }
}
