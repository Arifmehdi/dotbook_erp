<?php

namespace Modules\HRM\Interface;

interface SubSectionServiceInterface extends BaseServiceInterface
{
    public function getSubSectionBySection(int $id);

    public function getSubSectionDoPluck(int $id);
}
