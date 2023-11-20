<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentType extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\PaymentTypesFactory::new();
    }

    public function elPayment()
    {
        return $this->hasMany(ELPayment::class);
    }
}
