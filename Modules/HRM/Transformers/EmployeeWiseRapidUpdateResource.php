<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeWiseRapidUpdateResource extends JsonResource
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
            'employee' => $request['employee'],
            'months' => $request['month'],
            'year' => $request['year'],
            'attendances' => $request['attendances'],
            'attendance_dates' => $request['attendance_dates'],
            'fallback_shifts' => $request['fallback_shifts'],
            'shift_adjustments' => $request['shift_adjustments'],
        ];
    }
}
