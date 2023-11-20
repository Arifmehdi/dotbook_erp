<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\HRM\Http\Requests\HrmDepartment\CreateHrmDepartmentRequest;
use Modules\HRM\Http\Requests\HrmDepartment\UpdateHrmDepartmentRequest;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Modules\HRM\Transformers\HrmDepartmentResource;

class HrmDepartmentController extends Controller
{
    private $departmentService;

    public function __construct(HrmDepartmentServiceInterface $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $departments = HrmDepartmentResource::collection($this->departmentService->all());

        return $departments;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateHrmDepartmentRequest $request)
    {
        $data = $this->departmentService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'HrmDepartment Saved successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $department = HrmDepartmentResource::make($this->departmentService->find($id));

        return $department;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateHrmDepartmentRequest $request, $id)
    {
        $data = $this->departmentService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'HrmDepartment Updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $department = $this->departmentService->trash($id);

        return response()->json(['message' => 'HrmDepartment Deleted successfully']);
    }

    /**
     * Permanent Delete the department Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $departments = HrmDepartmentResource::collection($this->departmentService->getTrashedItem());

        return $departments;
    }

    /**
     * Permanent Delete the department Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $department = $this->departmentService->permanentDelete($id);

        return response()->json(['message' => 'HrmDepartment is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $department = $this->departmentService->restore($id);

        return response()->json(['message' => 'HrmDepartment restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->hrm_department_id)) {
            if ($request->action_type == 'move_to_trash') {
                $department = $this->departmentService->bulkTrash($request->hrm_department_id);

                return response()->json(['message' => 'HrmDepartments are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $department = $this->departmentService->bulkRestore($request->hrm_department_id);

                return response()->json(['message' => 'HrmDepartments are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $department = $this->departmentService->bulkPermanentDelete($request->hrm_department_id);

                return response()->json(['message' => 'HrmDepartments are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
