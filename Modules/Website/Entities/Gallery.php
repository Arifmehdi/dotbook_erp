<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends BaseModel
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Website\Database\factories\GalleryFactory::new();
    }

    public function gallery_category()
    {
        return $this->belongsTo(GalleryCategory::class);
    }
}
