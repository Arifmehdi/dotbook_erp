<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ShiftChangeResource extends JsonResource
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
            'present_address' => \strip_tags(\str_replace('\n', '', $this->present_address)),
            'department_name' => $this->hrmDepartment->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'shift_id' => $this->shift->id,
        ];
    }
}
