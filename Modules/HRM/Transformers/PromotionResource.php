<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
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
            'employee_name' => $this->employee->name,
            'employee_id' => $this->employee_id,
            'photo' => $this->photo,
            'section_name' => $this->section->name,
            'department_name' => $this->hrmDepartment->name,
            'phone' => $this->phone,
            'promoted_by' => $this->promoted_by,
            'promoted_date' => $this->promoted_date,
            'auth_id' => $this->auth_id,
        ];
    }
}
