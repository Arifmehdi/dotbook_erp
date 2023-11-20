<?php

namespace Modules\HRM\Interface;

interface AwardServiceInterface extends BaseServiceInterface
{
    public function awardEmployeeFilter($request);
}
