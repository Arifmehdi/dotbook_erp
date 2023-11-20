<?php

namespace Modules\Communication\Entities;

class EmailServer extends BaseModel
{
    protected $fillable = ['server_name', 'host', 'port', 'user_name', 'password', 'encryption', 'address', 'name'];
}
