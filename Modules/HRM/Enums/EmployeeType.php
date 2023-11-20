<?php

namespace Modules\HRM\Enums;

enum EmployeeType: int
{
    case Admin = 1;
    case Staff = 2;
    case Employee = 3;
    case Worker = 4;
    case Other = 5;
}
