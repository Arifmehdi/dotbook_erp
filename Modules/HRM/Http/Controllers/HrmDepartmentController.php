<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Entities\HrmDepartment;
use Modules\HRM\Http\Requests\HrmDepartment\CreateHrmDepartmentRequest;
use Modules\HRM\Http\Requests\HrmDepartment\UpdateHrmDepartmentRequest;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class HrmDepartmentController extends Controller
{
    public function __construct(
        private HrmDepartmentServiceInterface $departmentService
    ) {
    }

    public function index(Request $request)
    {
        $this->authorize('hrm_departments_index');
        if ($request->showTrashed == 'true') {
            $departments = $this->departmentService->getTrashedItem();
        } else {
            $departments = $this->departmentService->all();
        }

        $rowCount = $this->departmentService->getRowCount();
        $trashedCount = $this->departmentService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($departments)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '<div class="icheck-primary text-center">
                                <input type="checkbox" name="hrm_department_id[]" value="' . $row->id . '" class="mt-2 check1">
                            </div>';
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $action1 = '';
                    $action2 = '';
                    $type = '';
                    $icon1 = '';
                    $icon2 = '';
                    if ($row->trashed()) {
                        $action1 = 'restore';
                        $action2 = 'permanent-delete';
                        $type = 'restore';
                        $icon1 = '<i class="fa-solid fa-recycle"></i>';
                        $icon2 = '<i class="fa-solid fa-trash-check"></i>';
                    } else {
                        $action1 = 'edit';
                        $action2 = 'destroy';
                        $type = 'Edit';
                        $icon1 = '<span class="fas fa-edit"></span></a>';
                        $icon2 = '<span class="fas fa-trash "></span>';
                    }
                    $html = '<div class="dropdown table-dropdown">';
                    if (auth()->user()->can('hrm_departments_update')) {
                        $html .= '<a href="' . route('hrm.departments.' . $action1, $row->id) . '" class="action-btn c-edit ' . $action1 . '" id="' . $action1 . '_department" title="' . $type . '">' . $icon1 . '</a>';
                    }
                    if (auth()->user()->can('hrm_departments_delete')) {
                        $html .= '<a href="' . route('hrm.departments.' . $action2, $row->id) . '" class="action-btn c-delete delete" id="delete_department" title="Delete">' . $icon2 . '</a>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action', 'check'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::hrm_departments.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateHrmDepartmentRequest $request)
    {
        $department = $this->departmentService->store($request->validated());
        return response()->json('Department created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $department = $this->departmentService->find($id);
        return view('hrm::hrm_departments.ajax_views.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateHrmDepartmentRequest $request, $id)
    {
        $department = $this->departmentService->update($request->validated(), $id);
        return response()->json('Department updated successfully');
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
        return response()->json('Department deleted successfully');
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
        return response()->json('Department is permanently deleted successfully');
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
        return response()->json('Department restored successfully');
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

                return response()->json('Departments are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $department = $this->departmentService->bulkRestore($request->hrm_department_id);

                return response()->json('Departments are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $department = $this->departmentService->bulkPermanentDelete($request->hrm_department_id);

                return response()->json('Departments are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
