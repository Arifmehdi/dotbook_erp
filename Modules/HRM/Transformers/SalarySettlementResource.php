<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SalarySettlementResource extends JsonResource
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
            'employee_id' => $this->employee_id,
            'name' => $this->name,
            'section_name' => $this->section->name ?? 'Section is not Specified',
            'department_name' => $this->hrmDepartment->name,
            'designation_name' => $this->designation->name,
            'phone' => $this->phone,
            'salary' => $this->salary,
        ];
    }
}
