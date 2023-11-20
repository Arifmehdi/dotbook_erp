<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Modules\HRM\Entities\HrmDepartment;

class SoftDeleteController extends Controller
{
    public function forModel($resource)
    {
        $model = null;
        switch ($resource) {
            case 'hrm_departments':
                $model = HrmDepartment::class;
                break;

            default:
                $model = null;
                break;
        }
    }

    public function allTrash($resource)
    {

        $model = $this->forModel($resource);
        $trashedItems = $model->withTrashed();

        return response()->json([
            'message' => 'All trash',
        ]);
    }
}
