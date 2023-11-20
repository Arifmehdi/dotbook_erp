<?php

namespace Modules\HRM\Interface;

interface LeaveServiceInterface
{
    public function getAllLeavesByYear(int|string $year): iterable;

    public function getAllLeavesByMonthYear(string $month, int|string $year): iterable;

    public function getLeavesByIdAndMonthYear(int|string $id, string $month, int|string $year): iterable;

    public function getLeavesByEmployeeIdAndMonthYear(int|string $employee_id, string $month, int|string $year): iterable;

    public function getTypeWiseYearlyLeaves($employee_id, $year): iterable;
}
