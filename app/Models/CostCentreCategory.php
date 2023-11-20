<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostCentreCategory extends Model
{
    public function parentCategory()
    {
        return $this->belongsTo(CostCentreCategory::class, 'parent_category_id');
    }

    public function subCategories()
    {
        return $this->hasMany(CostCentreCategory::class, 'parent_category_id')->with(['costCentres', 'subCategories', 'subCategories.costCentres', 'subCategories.parentCategory']);
    }

    public function costCentres()
    {
        return $this->hasMany(CostCentre::class, 'category_id');
    }
}
