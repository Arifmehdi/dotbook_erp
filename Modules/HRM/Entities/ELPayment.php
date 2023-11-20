<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ELPayment extends BaseModel
{
    use HasFactory;

    protected $table = 'el_payments';

    protected $fillable = [
        'employee_id',
        'year',
        'el_days',
        'payment_date',
        'payment_amount',
        'payment_type_id',
        'remarks',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\ELPaymentFactory::new();
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }
}
