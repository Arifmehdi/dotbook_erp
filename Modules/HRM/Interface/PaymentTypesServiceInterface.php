<?php

namespace Modules\HRM\Interface;

interface PaymentTypesServiceInterface extends BaseServiceInterface
{
    public function allowedPayment();
}
