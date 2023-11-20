<?php

namespace Modules\CRM\Entities;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends BaseModel
{
    use HasFactory;

    protected $fillable = ['billing_plan', 'quantity', 'date', 'subscription_name', 'description', 'customer_id', 'project_id', 'currency', 'tax', 'terms'];

    public function customers()
    {
        return $this->setConnection('mysql')->belongsTo(Customer::class, 'customer_id');
    }

    protected static function newFactory()
    {
        return \Modules\CRM\Database\factories\SubscriptionFactory::new();
    }
}
