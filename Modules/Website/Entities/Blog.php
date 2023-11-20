<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends BaseModel
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Website\Database\factories\BlogFactory::new();
    }

    public function blog_categories()
    {
        return $this->belongsTo(BlogCategories::class);
    }
}
