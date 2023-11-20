<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class NoticeResource extends JsonResource
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
            'description' => $this->description,
            'attachment' => $this->attachment,
            'notice_by' => $this->notice_by,
            'is_active' => $this->is_active,
        ];
    }
}
