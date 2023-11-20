<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceRapidUpdateResource extends JsonResource
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
            'clock_in' => $this->clock_in,
            'clock_out' => $this->clock_out,
            'status' => $this->status,
            'year' => $this->year,
            'month' => $this->month,
            'shifts' => $this->shifts,
            'attendance_dates' => $this->attendance_dates,
        ];
    }
}
