<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseModel
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Website\Database\factories\ProductFactory::new();
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
