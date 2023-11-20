<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\HRM\Entities\Designation;
use Modules\HRM\Entities\Employee;

class ParentOrganogramResource extends JsonResource
{
    public static $wrap = 'nodeStructure';

    public function showAvatarImage($imagePath, $imageName)
    {
        $fileExists = file_exists($imagePath . $imageName);

        if (!is_null($imageName)) {
            return $fileExists ? asset($imagePath . $imageName) : asset('images/profile-picture.jpg');
        } else {
            return asset('images/profile-picture.jpg');
        }
    }

    public function with($request)
    {
        return [
            'chart' => [
                'container' => '#basic-example',
                'hideRootNode' => true,
                'siblingSeparation' => 40,
                'subTeeSeparation' => 30,
                'connectors' => [
                    'type' => 'step',
                ],
                'node' => [
                    'HTMLclass' => 'nodeExample1',
                ],
            ],

        ];
    }

    /**
     * Designation Child Designation With Employee Details.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data['text'] = [
            'name' => 'root_name',
            'title' => 'root_title',
            'contact' => 'root_contact',
        ];
        $data['image'] = 'root_image';
        $data['stackChildren'] = true;
        $data['children'] = OrganogramResource::collection($this);

        return $data;
    }
}
