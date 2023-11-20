<?php

namespace Modules\HRM\Interface;

interface AttendanceRapidUpdateServiceInterface
{
    public function dateWiseRapidUpdate(array $attributes);

    public function employeeWiseRapidUpdate(array $attributes);
}
