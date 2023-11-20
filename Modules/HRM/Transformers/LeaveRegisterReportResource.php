<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRegisterReportResource extends JsonResource
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
            'employee' => $this->employee,
            'opening_balance' => $this->opening_balance,
            'leaves' => $this->leaves,
        ];
    }
}
