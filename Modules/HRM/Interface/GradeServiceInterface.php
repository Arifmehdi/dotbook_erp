<?php

namespace Modules\HRM\Interface;

interface GradeServiceInterface extends BaseServiceInterface
{
    public function calculateGrossSalary(int $id);
}
