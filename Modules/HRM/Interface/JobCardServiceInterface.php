<?php

namespace Modules\HRM\Interface;

interface JobCardServiceInterface
{
    public function jobCardPrint($request);

    public function calculateJobCard($user_id, $month, $year, $leaveApplicationService, $offDaysService);
}
