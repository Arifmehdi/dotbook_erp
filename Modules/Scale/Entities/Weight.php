<?php

namespace Modules\Scale\Entities;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['client_id', 'weight_type'];

    public function weightClient()
    {
        return $this->belongsTo(WeightClient::class, 'client_id');
    }

    public function weightDetails()
    {
        return $this->hasMany(WeightDetails::class, 'weight_scale_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    protected static function newFactory()
    {
        return \Modules\Scale\Database\factories\WeightFactory::new();
    }
}
