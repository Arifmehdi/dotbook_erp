<?php

namespace App\Models\Essential;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class WorkspaceUsers extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'prefix', 'name', 'last_name');
    }
}
