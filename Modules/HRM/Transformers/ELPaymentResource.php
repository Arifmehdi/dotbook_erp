<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ELPaymentResource extends JsonResource
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
            'year' => $this->year,
            'el_days' => $this->el_days,
            'payment_date' => $this->payment_date,
            'payment_amount' => $this->payment_amount,
            'payment_type_id' => $this->payment_type_id,
            'remarks' => $this->remarks,
            'status' => $this->status,
        ];
    }
}
