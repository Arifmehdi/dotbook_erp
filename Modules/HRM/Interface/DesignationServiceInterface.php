<?php

namespace Modules\HRM\Interface;

interface DesignationServiceInterface extends BaseServiceInterface
{
    public function getDesignationBySection(int $id);

    public function designationSelectedAndSortListWithId();
}
