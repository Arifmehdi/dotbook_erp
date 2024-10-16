<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ResignEmployeesResource extends JsonResource
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
            'present_address' => $this->present_address,
            'section_id' => $this->section_id,
            'designation_id' => $this->designation_id,
            'joining_date' => $this->joining_date,
            'resign_date' => $this->resign_date,
            'phone' => $this->phone,
        ];
    }
}
