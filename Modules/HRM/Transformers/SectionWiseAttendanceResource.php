<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionWiseAttendanceResource extends JsonResource
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
            'at_date' => $this->at_date,
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee?->name,
            'section_name' => $this->employee?->section?->name,
            'department_name' => $this->employee?->department?->name,
            'clock_in' => $this->clock_in,
            'clock_out' => $this->clock_out,
            'status' => $this->status,
        ];
    }
}
