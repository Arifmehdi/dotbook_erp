<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SalaryAdvanceResource extends JsonResource
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
            'permitted_by' => $this->permitted_by,
            'date' => $this->date,
            'amount' => $this->amount,
            'month' => $this->month,
            'year' => $this->year,
            'detail' => $this->description,
        ];
    }
}
