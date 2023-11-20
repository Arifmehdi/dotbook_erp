<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ShiftAdjustmentResource extends JsonResource
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
            'shift_id' => $this->shift_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'late_count' => $this->late_count,
            'applied_date_from' => $this->applied_date_from,
            'applied_date_to' => $this->applied_date_to,
            'with_break' => $this->with_break,
            'break_start' => $this->break_start,
            'break_end' => $this->break_end,
        ];
    }
}
