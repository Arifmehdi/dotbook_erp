<?php

namespace Modules\Core\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BdDistrictResource extends JsonResource
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
            'bn_name' => $this->bn_name,
            'url' => $this->url,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'division_name' => $this->bdDivision->name,
            // 'bd_division' => BdDivisionResource::make($this->bdDivision),
        ];
    }
}
