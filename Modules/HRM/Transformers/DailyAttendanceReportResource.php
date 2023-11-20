<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DailyAttendanceReportResource extends JsonResource
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
            'employee_name' => $this->employee->name,
            'section_id' => $this->section_id,
            'hrm_department_id' => $this->hrm_department_id,
            'designation_id' => $this->designation_id,
            'sub_section_id' => $this->sub_section_id,
            'clock_in' => $this->clock_in,
            'clock_out' => $this->clock_out,
            'shift' => $this->shift,
            'date_range' => $this->date_range,
            'status' => $this->status,
        ];
    }
}
