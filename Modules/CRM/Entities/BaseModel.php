<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'crm';

    public $modelNotFoundMessage = 'Data not be found with given id(s).';
}
