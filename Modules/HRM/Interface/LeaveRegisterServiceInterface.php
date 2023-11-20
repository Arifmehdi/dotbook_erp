<?php

namespace Modules\HRM\Interface;

interface LeaveRegisterServiceInterface
{
    public function getYearlyLeaveOpening($employee, int $year): mixed;
}
