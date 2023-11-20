<?php

namespace Modules\HRM\Enums;

enum EmployeeDutyType: int
{
    case FullTime = 0;
    case PartTime = 1;
    case Contractual = 2;
    case Other = 3;
}
