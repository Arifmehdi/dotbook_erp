<?php

namespace Modules\HRM\Interface;

interface SalaryServiceInterface
{
    public function calculateSalary(iterable $employees_collection, ?int $_division_id, ?int $year, ?int $_month_number, string $startDate = null, string $endDate = null): iterable;
}
