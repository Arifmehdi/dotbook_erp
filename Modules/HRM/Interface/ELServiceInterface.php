<?php

namespace Modules\HRM\Interface;

interface ELServiceInterface
{
    public function getAll_EL(int $employeeId, array $years): array;

    public function getYearlyELInDetail(int $employeeId, int $year): array;

    public function getYearlyEL(int $employeeId, int $year): array;

    public function getEL_Calculation($year);
}
