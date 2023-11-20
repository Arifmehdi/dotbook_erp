<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class TrashEmployeesResource extends JsonResource
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
            'name' => $this->name,
            'section_name' => $this->section->name,
            'department' => $this->hrmDepartment->name,
            'present_address' => $this->present_address,
            'mobile_banking_account_number' => $this->mobile_banking_account_number,
            'phone' => $this->phone,
        ];
    }
}
