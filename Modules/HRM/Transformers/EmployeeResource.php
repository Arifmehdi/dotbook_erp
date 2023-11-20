<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'photo' => $this->photo,
            'name' => $this->name,
            'present_address' => \strip_tags(\str_replace('\n', '', $this->present_address)),
            'section_name' => $this->section->name,
            'department_name' => $this->hrmDepartment->name,
            'phone' => $this->phone,
            'employment_status' => $this->employment_status,
            'mobile_banking_account_number' => $this->mobile_banking_account_number,
            'joining_date' => $this->joining_date,
        ];
    }
}
