<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AwardResource extends JsonResource
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
            'award_name' => $this->award_name,
            'award_description' => $this->award_description,
            'gift_item' => $this->gift_item,
            'award_by' => $this->award_by,
            'date' => $this->date,
            'month' => $this->month,
            'year' => $this->year,
        ];
    }
}
