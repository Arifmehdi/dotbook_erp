<?php

namespace Modules\HRM\Interface;

interface OffDaysRepositoryInterface
{
    public function getMonthlyOffDays(string $month, int $year): iterable;

    public function getYearlyOffDays(int $year): iterable;
}
