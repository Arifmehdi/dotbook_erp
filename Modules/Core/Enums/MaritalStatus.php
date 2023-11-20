<?php

namespace Modules\Core\Enums;

enum MaritalStatus: string
{
    case Married = 'Married';
    case Single = 'Single';
    case Divorced = 'Divorced';
    case Other = 'Other';
}
