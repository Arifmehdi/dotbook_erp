<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Utils\DateTimeUtils;
use Modules\HRM\Entities\Attendance;
use Modules\HRM\Http\Requests\LeaveApplication\CreateLeaveApplicationRequest;
use Modules\HRM\Http\Requests\LeaveApplication\UpdateLeaveApplicationRequest;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\LeaveApplicationServiceInterface;
use Modules\HRM\Interface\LeaveTypeServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class LeaveApplicationController extends Controller
{
    private $leaveApplicationService;

    private $employeeService;

    private $leaveTypeService;

    public function __construct(LeaveApplicationServiceInterface $leaveApplicationService, EmployeeServiceInterface $employeeService, LeaveTypeServiceInterface $leaveTypeService)
    {
        $this->leaveApplicationService = $leaveApplicationService;
        $this->employeeService = $employeeService;
        $this->leaveTypeService = $leaveTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $rowCount = $this->leaveApplicationService->getRowCount();
        $trashedCount = $this->leaveApplicationService->getTrashedCount();
        if ($request->ajax()) {
            $filter_request = $request->validate([
                'employee_id' => 'nullable',
                'date_range' => 'nullable',
                'leave_type_id' => 'nullable',
                'type' => 'nullable',
            ]);

            if ($request->showTrashed == 'true') {
                $leave_applications = $this->leaveApplicationService->getTrashedItem($filter_request);
            } else {
                $leave_applications = $this->leaveApplicationService->allLeaveApplication($filter_request);
            }

            return DataTables::of($leave_applications)
                ->addIndexColumn()
                ->addColumn('employeeName', function ($row) {
                    return $row->employee->name ?? '';
                })
                ->addColumn('employee_id', function ($row) {
                    return $row->employee->employee_id ?? '';
                })
                ->addColumn('leave_type_name', function ($row) {
                    return "<span class='d-block text-center'>". $row->leaveType->name ."</span>" ?? '';
                })
                
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                <input type="checkbox" name="leave_application_id[]" value="'.$row->id.'" class="mt-2 check1">
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

                    if (auth()->user()->can('hrm_leave_applications_update')) {
                        $html .= '<a href="'.route('hrm.leave-applications.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_leave_application" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_leave_applications_delete')) {
                        $html .= '<a href="'.route('hrm.leave-applications.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_leave_application" title="Delete">'.$icon2.'</a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class=" d-block text-center"></span><span class="badge bg-primary text-white" >Allowed</span>' : '<span class="d-block text-center"></span><span class="badge bg-info text-white">Not-Allowed</span>';
                })
                ->addColumn('approve_day', function ($row) {
                    return "<span class='d-block text-center'>".$row->approve_day."</span>"; 
                    
                })

                ->addColumn('isPaid', function ($row) {
                    return ($row->is_paid == 0)
                    ? "<span  class='d-block text-center' title='Unpaid'><i class='fa-sharp fa-regular fa-circle-xmark fa-lg text-danger'></i></span>"
                    : "<span  class='d-block text-center' title='Paid'><i class='fa-sharp fa-regular fa-circle-check fa-lg text-success'></i></span>";
                })
                ->addColumn('attachment', function ($row) {
                    return ($row->attachment != null && pathinfo($row->attachment, PATHINFO_EXTENSION) == 'pdf')
                        ? '<a href="'.route('hrm.leave_application.show', $row->id).'"  class="d-block text-center showModalbtn"><i class="fa-sharp fa-regular fa-circle-check fa-lg text-success"></i></a>'  
                        : "<span' class='d-block text-center'><i class='fa-sharp fa-regular fa-circle-xmark fa-lg text-danger'></i></span>";
                })
                ->rawColumns(['action', 'check', 'status', 'isPaid', 'attachment','leave_type_name', 'approve_day'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }
        $employees = $this->employeeService->employeeActiveListWithId();
        $leaveTypes = $this->leaveTypeService->allowedLeaveType();

        return view('hrm::leave_applications.index', compact('employees', 'leaveTypes'));
    }

    public function store(CreateLeaveApplicationRequest $request)
    {
        // Check if already present in requested leave days
        $newDates = DateTimeUtils::dateRange($request->from_date, $request->to_date, 'd-m-Y');
        $res = Attendance::where('employee_id', $request->employee_id)
            ->whereIn('status', ['Present', 'Late'])
            ->whereIn('at_date', $newDates)
            ->count();
        if (isset($res) and $res != 0) {
            return response()->json('Employee already present in selected date(s). Check the job-card!', 409);
        }
        $leaveApplication = $this->leaveApplicationService->store($request->validated());

        return response()->json('Leave Application created successfully');
    }

    public function edit($id)
    {
        $employees = $this->employeeService->employeeActiveListWithId();
        $leaveTypes = $this->leaveTypeService->allowedLeaveType();
        $leaveApplication = $this->leaveApplicationService->find($id);

        return view('hrm::leave_applications.ajax_views.edit', compact('employees', 'leaveTypes', 'leaveApplication'));
    }

    public function update(UpdateLeaveApplicationRequest $request, $id)
    {
        // Check if already present in requested leave days
        $newDates = DateTimeUtils::dateRange($request->from_date, $request->to_date, 'd-m-Y');
        $res = Attendance::where('employee_id', $request->employee_id)
            ->whereIn('status', ['Present', 'Late'])
            ->whereIn('at_date', $newDates)
            ->count();
        if (isset($res) and $res != 0) {
            return response()->json('Employee already present in selected date(s). Check the job-card!', 409);
        }
        $leaveApplication = $this->leaveApplicationService->update($request->validated(), $id);

        return response()->json('Leave Application updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $leaveApplication = $this->leaveApplicationService->trash($id);

        return response()->json('Leave Application deleted successfully');
    }

    public function permanentDelete($id)
    {
        $holiday = $this->leaveApplicationService->permanentDelete($id);

        return response()->json('Leave Application is permanently deleted successfully');
    }

    public function restore($id)
    {
        $holiday = $this->leaveApplicationService->restore($id);

        return response()->json('Leave Application restored successfully');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->leave_application_id)) {
            if ($request->action_type == 'move_to_trash') {
                $leaveType = $this->leaveApplicationService->bulkTrash($request->leave_application_id);

                return response()->json('Leave Application are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $leaveType = $this->leaveApplicationService->bulkRestore($request->leave_application_id);

                return response()->json('Leave Application are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $leaveType = $this->leaveApplicationService->bulkPermanentDelete($request->leave_application_id);

                return response()->json('Leave Application are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function leaveAttachment(int $id)
    {
        $holidays = $this->leaveApplicationService->find($id);
        return view('hrm::leave_applications.ajax_views.show', compact('holidays'));
        dd($holidays->attachment);
    }
}
