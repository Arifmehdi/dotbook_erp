<?php

namespace Modules\CRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessLeadResource extends JsonResource
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
            'location' => $this->location,
        ];
    }
}
