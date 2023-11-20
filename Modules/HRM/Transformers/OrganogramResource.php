<?php

namespace Modules\HRM\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\HRM\Entities\Designation;
use Modules\HRM\Entities\Employee;

class OrganogramResource extends JsonResource
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
            'name' => $this->employees->first()->name ?? '',
            'title' => $this->name,
            'contact' => $this->employees->first()->phone ?? '',
        ];
        $data['image'] = $this->showAvatarImage('uploads/employees/', ($this->employees->first()->photo ?? null));

        if (!$this->child_designation->isEmpty()) {
            $data['stackChildren'] = true;
            $data['children'] = $this->formatChildDesignations($this->child_designation);
        }

        //$data['text'] = $this->child_designation;
        return $data;
    }

    /**
     * Format the child designations recursively.
     *
     * @param  \Illuminate\Support\Collection  $childDesignations
     * @return array
     */
    protected function formatChildDesignations($childDesignations)
    {
        return $childDesignations->map(function ($designation) {

            $data = [
                'text' => [
                    'name' => $designation->employees->first()->name ?? 'No Employee',
                    'title' => $designation->name,
                    'contact' => $designation->employees->first()->phone ?? '',
                ],
                'image' => $this->showAvatarImage('uploads/employees/', ($designation->employees->first()->photo ?? null)),
            ];
            if (!$designation->child_designation->isEmpty()) {
                $data['stackChildren'] = true;
                $data['children'] = $this->formatChildDesignations($designation->child_designation);
            }

            return $data;
        });
    }
}
