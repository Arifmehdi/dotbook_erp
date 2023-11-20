<?php

namespace Modules\CRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class IndividualLeadResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'files' => $this->files,
            'description' => $this->description,
            'companies' => $this->companies,
            'phone_numbers' => $this->phone_numbers,
        ];
    }
}
