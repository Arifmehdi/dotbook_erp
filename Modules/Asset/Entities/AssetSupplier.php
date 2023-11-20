<?php

namespace Modules\Asset\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetSupplier extends Model
{
    use HasFactory;

    protected $table = 'assets_suppliers';
}
