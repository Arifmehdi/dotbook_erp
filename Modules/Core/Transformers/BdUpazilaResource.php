<?php

namespace Modules\Core\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BdUpazilaResource extends JsonResource
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
            'district_name' => $this->bdDistrict->name,
            // 'division_name' => $this->bdDistrict->bdDivision->name,
            // 'bd_district' => BdDistrictResource::make($this->bdDistrict),
        ];
    }
}
