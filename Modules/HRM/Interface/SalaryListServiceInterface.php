<?php

namespace Modules\HRM\Interface;

interface SalaryListServiceInterface
{
    public function payrollList($request);

    public function salaryListRequestFilter($request);
}
