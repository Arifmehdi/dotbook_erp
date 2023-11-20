<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SubSectionResource extends JsonResource
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
            'section' => SectionResource::make($this->section),
        ];
    }
}
