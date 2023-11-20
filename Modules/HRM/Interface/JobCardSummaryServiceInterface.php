<?php

namespace Modules\HRM\Interface;

interface JobCardSummaryServiceInterface
{
    public function jobSummaryPrint($request); //: Renderable

    public function calculateSummaryFilter($request); //array

    public function calculateSummary($employees, $division_id, $month, $year, $offDaysService, $leaveApplicationService): array;
}
