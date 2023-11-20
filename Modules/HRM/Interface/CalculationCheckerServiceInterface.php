<?php

namespace Modules\HRM\Interface;

interface CalculationCheckerServiceInterface
{
    public function checkJobCardAndSalary($request);

    public function checkSummaryAndSalary($request);

    public function checkAllCalculation($request);
}
