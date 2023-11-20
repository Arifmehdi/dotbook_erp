<?php

namespace Modules\HRM\Interface;

interface LeaveApplicationServiceInterface extends BaseServiceInterface
{
    public function getMonthLeaves(string $month, int $year): iterable;

    public function getEmployeeLeaves(string $user_id, string $month, int $year): iterable;

    public function getEmployeesLeaves(array $user_ids, string $month, int $year): iterable;

    public function getUniqueLeaves(string $userId, iterable $leaves): array;

    public function allLeaveApplication(array $filter_request);
}
