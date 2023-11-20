<?php

namespace Modules\HRM\Entities;

class Setting extends BaseModel
{
    protected $fillable = ['key', 'value'];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\SettingFactory::new();
    }
}
