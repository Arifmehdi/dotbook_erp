<?php

namespace Modules\HRM\Enums;

enum EmploymentStatus: int
{
    case Active = 1;
    case Delete = 4;
    case Left = 3;
    case Resign = 2;
    case Trash = 0;
}
