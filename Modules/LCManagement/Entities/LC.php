<?php

namespace Modules\LCManagement\Entities;

use App\Models\Bank;
use App\Models\Currency;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LC extends Model
{
    protected $table = 'lcs';

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function issuingBank()
    {
        return $this->belongsTo(Bank::class, 'issuing_bank_id');
    }

    public function openingBank()
    {
        return $this->belongsTo(Bank::class, 'opening_bank_id');
    }

    public function advisingBank()
    {
        return $this->belongsTo(Bank::class, 'advising_bank_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
