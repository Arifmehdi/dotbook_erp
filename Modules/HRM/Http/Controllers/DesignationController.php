<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Designation;
use Modules\HRM\Http\Requests\Designation\CreateDesignationRequest;
use Modules\HRM\Http\Requests\Designation\UpdateDesignationRequest;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Modules\HRM\Interface\DesignationServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class DesignationController extends Controller
{
    public function __construct(
        private DesignationServiceInterface $designationService,
        private HrmDepartmentServiceInterface $departmentService,
    ) {
    }

    public function index(Request $request)
    {
        $departments = $this->departmentService->all()->get();

        if ($request->showTrashed == 'true') {
            $designations = $this->designationService->getTrashedItem();
        } else {
            $designations = $this->designationService->all();
        }

        $rowCount = $this->designationService->getRowCount();
        $trashedCount = $this->designationService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($designations)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="designation_id[]" value="' . $row->id . '" class="mt-2 check1">
                                </div>';
                    return $html;
                })
                ->addColumn('sectionName', function ($row) {
                    return $row->sections->name ?? 'No Section';
                })
                ->addColumn('parentDesignation', function ($row) {
                    return $row->parent_designation->name ?? '';
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

                    if (auth()->user()->can('hrm_designations_update')) {
                        $html .= '<a href="' . route('hrm.designations.' . $action1, $row->id) . '" class="action-btn c-edit ' . $action1 . '" id="' . $action1 . '_designation" title="' . $type . '">' . $icon1 . '</a>';
                    }
                    if (auth()->user()->can('hrm_designations_delete')) {
                        $html .= '<a href="' . route('hrm.designations.' . $action2, $row->id) . '" class="action-btn c-delete delete" id="delete_designation" title="Delete">' . $icon2 . '</a>';
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
        return view('hrm::designations.index', compact('departments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function create()
    {
        $departments = $this->departmentService->all();
        $designations = $this->designationService->all();
        return view('hrm::designations.ajax_views.add', compact('departments', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateDesignationRequest $request)
    {
        $designation = $this->designationService->store($request->validated());

        return response()->json('Designation created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $designation = $this->designationService->find($id);
        $departments = $this->departmentService->all();
        $allDesignation = $this->designationService->all();

        return view('hrm::designations.ajax_views.edit', compact('designation', 'departments', 'allDesignation'));
    }

    /**
     * Get Designation By Section.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function getDesignationBySection($id)
    {
        $designation = $this->designationService->getDesignationBySection($id);

        return $designation;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateDesignationRequest $request, $id)
    {
        $designation = $this->designationService->update($request->validated(), $id);

        return response()->json('Designation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $designation = $this->designationService->trash($id);

        return response()->json('Designation deleted successfully');
    }

    /**
     * Permanent Delete the designation Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $designation = $this->designationService->permanentDelete($id);

        return response()->json('Designation is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $designation = $this->designationService->restore($id);

        return response()->json('Designation restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->designation_id)) {
            if ($request->action_type == 'move_to_trash') {
                $designation = $this->designationService->bulkTrash($request->designation_id);

                return response()->json('Designation are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $designation = $this->designationService->bulkRestore($request->designation_id);

                return response()->json('Designation are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $designation = $this->designationService->bulkPermanentDelete($request->designation_id);

                return response()->json('Designation are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
