<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
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
            'name' => $this->name,
            'basic' => $this->basic,
            'house_rent' => $this->house_rent,
            'medical' => $this->medical,
            'food' => $this->food,
            'transport' => $this->transport,
            'other' => $this->other,
            'gross_salary' => $this->gross_salary,
        ];
    }
}
