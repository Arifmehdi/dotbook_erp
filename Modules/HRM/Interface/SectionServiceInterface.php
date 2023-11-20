<?php

namespace Modules\HRM\Interface;

interface SectionServiceInterface extends BaseServiceInterface
{
    public function getSectionByHrmDepartment(int $id);

    public function sectionWithHrmDepartmentAndSelection();

    public function sectionSelectedAndSortListWithId();
}
