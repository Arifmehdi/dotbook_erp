<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $connection = 'hrm';

    public $modelNotFoundMessage = 'Data not be found with given id(s).';
}
