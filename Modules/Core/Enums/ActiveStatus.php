<?php

namespace Modules\Core\Enums;

enum ActiveStatus: string
{
    case ACTIVE = 'Active';
    case INACTIVE = 'Inactive';
    case PENDING = 'Pending';
    case REJECTED = 'Rejected';
}
