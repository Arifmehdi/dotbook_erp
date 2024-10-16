<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkVariantChild extends Model
{
    public function bulk_variant()
    {
        return $this->belongsTo(BulkVariant::class, 'bulk_variant_id');
    }
}
