<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Designation;
use Modules\HRM\Interface\OrganogramServiceInterface;

class OrganogramService implements OrganogramServiceInterface
{
    /**
     * Eg. $image = showImage('uploads/employees/', 'avatar.jpg');
     *
     * @return json Returns data with proper avatar Images and Details.
     */
    public function data()
    {
        abort_if(! auth()->user()->can('hrm_organogram_index'), 403, 'Access Forbidden');
        $data = Designation::with(['child_designation', 'employees'])->where('parent_designation_id', null)->get();

        return $data;
    }
}
