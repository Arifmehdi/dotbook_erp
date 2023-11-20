<?php

namespace Modules\HRM\Interface;

interface OffDaysServiceInterface
{
    public function getByMonth(string $month, int $year): iterable;

    public function getByYear(int $year): iterable;
}
