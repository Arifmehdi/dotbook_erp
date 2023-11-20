<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\Shift;
use Modules\HRM\Http\Requests\Shift\CreateShiftRequest;
use Modules\HRM\Http\Requests\Shift\UpdateShiftRequest;
use Modules\HRM\Interface\ShiftServiceInterface;
use Modules\HRM\Service\EmployeeService;
use Yajra\DataTables\Facades\DataTables;

class ShiftController extends Controller
{
    private $shiftService;

    private $employeeService;

    public function __construct(ShiftServiceInterface $shiftService, EmployeeService $employeeService)
    {
        $this->shiftService = $shiftService;
        $this->employeeService = $employeeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {

        if ($request->showTrashed == 'true') {
            $shifts = $this->shiftService->getTrashedItem();
        } else {
            $shifts = $this->shiftService->all();
        }

        $rowCount = $this->shiftService->getRowCount();
        $trashedCount = $this->shiftService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($shifts)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {

                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="shift_id[]" value="' . $row->id . '" class="mt-2 check1">
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
                        $icon2 = '<span class="fas fa-trash"></span>';
                    }

                    $html = '<div class="dropdown table-dropdown">';

                    if (auth()->user()->can('hrm_shifts_update')) {
                        $html .= '<a href="' . route('hrm.shifts.' . $action1, $row->id) . '" class="action-btn c-edit ' . $action1 . '" id="' . $action1 . '_shift" title="' . $type . '">' . $icon1 . '</a>';
                    }
                    if (auth()->user()->can('hrm_shifts_delete')) {
                        $html .= '<a href="' . route('hrm.shifts.' . $action2, $row->id) . '" class="action-btn c-delete delete" id="delete_shift" title="Delete">' . $icon2 . '</a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('start_time', function ($row) {
                    return date('h:i A', strtotime($row->start_time));
                })
                ->editColumn('late_count', function ($row) {
                    return date('h:i A', strtotime($row->late_count));
                })

                ->editColumn('end_time', function ($row) {
                    return date('h:i A', strtotime($row->end_time));
                })
                ->editColumn('is_allowed_overtime', function ($row) {
                    return $row->is_allowed_overtime == 1 ? '<span class="badge bg-primary text-white">Allowed</span>' : '<span class="badge bg-info text-white">Not-Allowed</span>';
                })
                ->rawColumns(['action', 'check', 'start_time', 'late_count', 'end_time', 'is_allowed_overtime'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::shifts.index', compact('shifts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateShiftRequest $request)
    {
        $shift = $this->shiftService->store($request->validated());

        return response()->json('Shift created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $shift = $this->shiftService->find($id);

        return view('hrm::shifts.ajax_views.edit', compact('shift'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateShiftRequest $request, $id)
    {
        $shift = $this->shiftService->update($request->validated(), $id);

        return response()->json('Shift updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $shift = $this->shiftService->trash($id);

        return response()->json('Shift deleted successfully');
    }

    /**
     * Permanent Delete the shift Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $shift = $this->shiftService->permanentDelete($id);

        return response()->json('Shift is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $shift = $this->shiftService->restore($id);

        return response()->json('Shift restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->shift_id)) {
            if ($request->action_type == 'move_to_trash') {
                $shift = $this->shiftService->bulkTrash($request->shift_id);

                return response()->json('Shifts are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $shift = $this->shiftService->bulkRestore($request->shift_id);

                return response()->json('Shifts are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $shift = $this->shiftService->bulkPermanentDelete($request->shift_id);

                return response()->json('Shifts are permanently deleted successfully');
            } else {
                return response()->json(['message' => 'Action is not specified.'], 401);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function shiftChange(Request $request)
    {
        abort_if(!auth()->user()->can('hrm_shift_change_index'), 403, 'Access Forbidden');
        $employees = $this->employeeService->activeEmployee();
        $shifts = $this->shiftService->all();

        if ($request->ajax()) {
            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {

                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="shift_id[]" value="' . $row->id . '" class="mt-2 check1">
                                </div>';

                    return $html;
                })
                ->editColumn('hrm_department_id', function ($row) {
                    return $row->hrmDepartment->name ?? 'HrmDepartment is not Specified';
                })
                ->editColumn('address', function ($row) {
                    return substr($row->PresentAddress, 0, 40) . ' ...' ?? null;
                })
                ->addColumn('shift_id', function ($row) use ($shifts) {
                    $html = '';
                    $html .= '<select class="dropdown_shift_id form-control form-select" id="' . $row->id . '" >';
                    foreach ($shifts as $shift) {
                        $html .= '<option class="text-dark" value="' . $shift->id . '" ';
                        if ($shift->id == $row->shift_id) {
                            $html .= 'selected';
                        }
                        $html .= '>' . $shift->name . '</option>';
                    }
                    $html .= '</select>';

                    return $html;
                })
                ->rawColumns(['check', 'hrm_department_id', 'shift_id', 'address'])

                ->smart(true)
                ->make(true);
        }

        return view('hrm::shifts.shift_change.index');
    }

    public function shiftChangeById($id, $employee_id)
    {
        abort_if(!auth()->user()->can('hrm_shift_change_action'), 403, 'Access Forbidden');
        Employee::where('id', $employee_id)->update(['shift_id' => $id]);

        return response()->json('Shift changed successfully!');
    }
}
