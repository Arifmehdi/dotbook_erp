<?php

namespace Modules\HRM\Interface;

use Modules\HRM\Entities\Employee;

interface FinalSettlementServiceInterface
{
    public function settleEmployee(Employee $employee, $submission_date, $approval_date, $stamp): array;
}
