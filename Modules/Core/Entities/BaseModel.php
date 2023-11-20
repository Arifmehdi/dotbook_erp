<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $connection = 'mysql';

    public $modelNotFoundMessage = 'Data not be found with given id(s).';
}
