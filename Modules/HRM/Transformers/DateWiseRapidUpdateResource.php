<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DateWiseRapidUpdateResource extends JsonResource
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
            'date' => $this->date,
            'fallback_shift' => $this->fallback_shift,
            'shift_adjustments' => $this->shift_adjustments,
            'attendances' => $this->attendances,
        ];
    }
}
