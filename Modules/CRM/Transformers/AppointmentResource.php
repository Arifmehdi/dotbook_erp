<?php

namespace Modules\CRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'schedule_date' => $this->schedule_date,
            'schedule_time' => $this->schedule_time,
            'customer_id' => $this->customer_id,
        ];
    }
}
