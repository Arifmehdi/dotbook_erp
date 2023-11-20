<?php

namespace Modules\HRM\Interface;

interface PromotionServiceInterface extends BaseServiceInterface
{
    public function promoteEmployeeBuilder();

    public function promoteEmployeeListAfterSort($request);
}
