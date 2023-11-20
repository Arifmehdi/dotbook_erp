<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModelWIthoutSoftDelete extends Model
{
    use HasFactory;

    protected $connection = 'hrm';

    public $modelNotFoundMessage = 'Data not be found with given id(s).';
}
