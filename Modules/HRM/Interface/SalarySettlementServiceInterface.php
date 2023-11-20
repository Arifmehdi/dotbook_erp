<?php

namespace Modules\HRM\Interface;

interface SalarySettlementServiceInterface
{
    public function store(array $attributes);

    public function departmentWiseStore(array $settlement);

    public function lastSettlementDelete(int $id);
}
