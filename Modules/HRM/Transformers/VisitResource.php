<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitResource extends JsonResource
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
            'title' => $this->title,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'category' => $this->category,
            'attachments' => $this->attachments,
            'description' => $this->description,
        ];
    }
}
