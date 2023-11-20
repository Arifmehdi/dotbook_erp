<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\LeaveType\CreateLeaveTypeRequest;
use Modules\HRM\Http\Requests\LeaveType\UpdateLeaveTypeRequest;
use Modules\HRM\Interface\LeaveTypeServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class LeaveTypeController extends Controller
{
    private $leaveTypeService;

    public function __construct(LeaveTypeServiceInterface $leaveTypeService)
    {
        $this->leaveTypeService = $leaveTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {

        if ($request->showTrashed == 'true') {
            $leaveTypes = $this->leaveTypeService->getTrashedItem();
        } else {
            $leaveTypes = $this->leaveTypeService->all();
        }
        $rowCount = $this->leaveTypeService->getRowCount();
        $trashedCount = $this->leaveTypeService->getTrashedCount();
        if ($request->ajax()) {
            return DataTables::of($leaveTypes)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->is_active) {
                        $status = 'Active';
                    } else {
                        $status = 'Disable';
                    }

                    return $status;
                })
                ->addColumn('recurrence', function ($row) {
                    if ($row->for_months === 1) {
                        $forMonths = 'Monthly';
                    } elseif ($row->for_months === 6) {
                        $forMonths = 'Bi Yearly';
                    } elseif ($row->for_months === 12) {
                        $forMonths = 'Yearly';
                    } else {
                        $forMonths = 'For '.$row->for_months.' Month';
                    }

                    return $forMonths;
                })
                ->editColumn('is_active', function ($row) {
                    return $row->is_active == 1 ? '<span class="badge bg-primary text-white">Allowed</span>' : '<span class="badge bg-info text-white">Not-Allowed</span>';
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="leave_type_id[]" value="'.$row->id.'" class="mt-2 check1">
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
                    if (auth()->user()->can('hrm_leave_types_update')) {
                        $html .= '<a href="'.route('hrm.leave-types.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_leave_type" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_leave_types_delete')) {
                        $html .= '<a href="'.route('hrm.leave-types.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_leave_type" title="Delete">'.$icon2.'</a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check', 'is_active'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::leave_types.index', compact('leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateLeaveTypeRequest $request)
    {
        $service = $this->leaveTypeService->store($request->validated());

        return response()->json('Leave Type created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $leaveType = $this->leaveTypeService->find($id);

        return view('hrm::leave_types.ajax_views.edit', compact('leaveType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateLeaveTypeRequest $request, $id)
    {
        $this->leaveTypeService->update($request->validated(), $id);

        return response()->json('Leave Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $leaveType = $this->leaveTypeService->trash($id);

        return response()->json('Leave Type deleted successfully');
    }

    /**
     * Permanent Delete the leaveType Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $leaveType = $this->leaveTypeService->permanentDelete($id);

        return response()->json('Leave Type is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $leaveType = $this->leaveTypeService->restore($id);

        return response()->json('Leave Type restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->leave_type_id)) {
            if ($request->action_type == 'move_to_trash') {
                $leaveType = $this->leaveTypeService->bulkTrash($request->leave_type_id);

                return response()->json('Leave Types are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $leaveType = $this->leaveTypeService->bulkRestore($request->leave_type_id);

                return response()->json('Leave Types are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $leaveType = $this->leaveTypeService->bulkPermanentDelete($request->leave_type_id);

                return response()->json('Leave Types are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
