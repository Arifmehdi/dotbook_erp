<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ELCalculationResource extends JsonResource
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
            'name' => $this->name,
            'section_name' => $this->section_name,
            'joining_date' => $this->joining_date,
            'phone' => $this->phone,
            'yearly_total_present' => $this->yearly_total_present,
            'yearly_el_count' => $this->yearly_el_count,
            'el_enjoyed' => $this->taken_el,
            'el_payable' => $this->payable_el,
            'daily_remuneration' => $this->daily_remuneration,
            'net_payable' => $this->net_payable,
        ];
    }
}
