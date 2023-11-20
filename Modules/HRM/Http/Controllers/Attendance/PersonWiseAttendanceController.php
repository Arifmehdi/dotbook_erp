<?php

namespace Modules\HRM\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\HRM\Entities\Attendance;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Http\Requests\Attendance\UpdateAttendanceRequest;
use Modules\HRM\Interface\AttendanceServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class PersonWiseAttendanceController extends Controller
{
    protected $attendanceService;

    protected $employeeService;

    public function __construct(AttendanceServiceInterface $attendanceService, EmployeeServiceInterface $employeeService)
    {
        $this->attendanceService = $attendanceService;
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {

        $employees = $this->employeeService->employeeActiveListWithId();
        if ($request->ajax()) {
            $attendances = $this->attendanceService->attendanceEmployee($request);
            $rowCount = $this->attendanceService->getRowCount();

            return DataTables::of($attendances)
                ->addIndexColumn()
                ->editColumn('section', function ($row) {
                    return $row->section_name;
                })
                ->addColumn('at_date_format', function ($row) {
                    // $at_date_formats = Carbon::parse($row->at_date)->format('Y-m-d');
                    $at_date_formats = date(config('hrm.date_format'), strtotime($row->at_date));

                    return $at_date_formats ?? '';
                })
                ->editColumn('employee_name', function ($row) {
                    return $row->employee_name ?? '';
                })
                ->editColumn('employee_id', function ($row) {
                    return $row->employeeId ?? '';
                })
                ->editColumn('shift', function ($row) {
                    return $row->shift ?? '';
                })
                ->editColumn('clock_in', function ($row) {
                    return Carbon::parse($row->clock_in)?->format(config('hrm.time_format')) ?? '';
                })
                ->editColumn('clock_out', function ($row) {
                    $clock_out = isset($row->clock_out) ? Carbon::parse($row->clock_out)->format(config('hrm.time_format')) : '<span class="d-block text-center"> -- </span>';

                    return $clock_out;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->employee_id ?? '';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item edit" href="'.route('hrm.persons.edit', $row->id).'" id="edit"><i class="fa-thin fa-pencil me-1"></i> Edit</a>';
                    $html .= '<a class="dropdown-item show" href="'.route('hrm.persons.show', $row->id).'" id="show"><i class="fa-thin fa-eye me-1"></i> View</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'section', 'clock_in', 'clock_out', 'shift', 'at_date_format'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::attendance.index', compact('employees'));
    }

    public function create(Request $request)
    {
        abort_if(! auth()->user()->can('hrm_person_wise_attendance'), 403, 'Access forbidden');
        $employees = $this->employeeService->employeeActiveListWithId();

        return view('hrm::attendance.create', compact('employees'));
    }

    public function attendanceCreate(Request $request)
    {
        $id = $request->employee_id;
        $date = $request->date;
        $currentRow = $request->currentRow;
        $employee = Employee::find($id);
        $attendance = Attendance::where('employee_id', $id)->whereDate('at_date_ts', $date)->first();

        return view('hrm::attendance.ajax_views.row_create', compact('attendance', 'employee', 'currentRow'));
    }

    public function departmentAttendance($hrm_department_id)
    {
        $employees = Employee::with('hrmDepartment')->where('hrm_department_id', $hrm_department_id)->get();

        return view('hrm::attendance.ajax_views.department_row_create', compact('employees'));
    }

    public function shiftAttendance($shift_id)
    {
        $employees = Employee::with('shift')->where('shift_id', $shift_id)->get();

        return view('hrm::attendance.ajax_views.shift_row_create', compact('employees'));
    }

    public function store(Request $request)
    {
        abort_if(! auth()->user()->can('hrm_person_wise_attendance_create'), 403, 'Access forbidden');
        $request->validate([
            'employee_ids' => 'required',
        ]);
        foreach ($request->employee_ids as $key => $employee_id) {
            $attendance = Attendance::whereDate('at_date_ts', $request->today)
                ->where('employee_id', $employee_id)
                ->orderBy('id', 'desc')
                ->first();

            if ($attendance) {
                $attendance->clock_out = $request->clock_outs[$key];
                if ($request->clock_outs[$key]) {
                    $attendance->clock_out_ts = $request->today.$request->clock_outs[$key];
                    $attendance->manual_entry = 1;
                }
                $attendance->save();
            } else {
                $data = new Attendance();
                $data->employee_id = $employee_id;
                $data->at_date = date('d-m-Y', strtotime($request->today));
                $data->at_date_ts = $request->today;
                $data->clock_in = $request->clock_ins[$key];
                $data->clock_in_ts = $request->today.$request->clock_ins[$key];
                $data->clock_out = $request->clock_outs[$key];
                if ($request->clock_outs[$key]) {
                    $data->clock_out_ts = $request->today.$request->clock_outs[$key];
                }
                $data->month = date('F', strtotime($request->today));
                $data->year = date('Y', strtotime($request->today));
                $shiftData = Employee::active()->with(['shift'])->where('id', '=', $data->employee_id)->first();
                $data->shift = $shiftData->shift->name;
                $data->shift_id = $shiftData->shift->id; // shift_id Foreign key
                $data->manual_entry = 1;
                if ($shiftData->shift->late_count) {
                    $office_late_time = strtotime($shiftData->shift->late_count);
                    $ci_time = strtotime($request->clock_ins[$key]);
                    if ($ci_time > $office_late_time) {
                        $data->status = 'Late';
                    } else {
                        $data->status = 'Present';
                    }
                }
                // dd($data);
                $data->save();
            }

        }

        return response()->json('Successfully Attendance is Added!');
    }

    public function show($id)
    {
        abort_if(! auth()->user()->can('hrm_person_wise_attendance_show'), 403, 'Access forbidden');
        $attendance = Attendance::where('id', $id)->first();

        return view('hrm::attendance.ajax_views.show', compact('attendance'));
    }

    public function edit($id)
    {
        abort_if(! auth()->user()->can('hrm_person_wise_attendance_edit'), 403, 'Access forbidden');
        $attendance = Attendance::with('employee')->where('id', $id)->first();

        return view('hrm::attendance.ajax_views.edit', compact('attendance'));
    }

    public function update(UpdateAttendanceRequest $request, $id)
    {
        $attendance = $this->attendanceService->update($request->validated(), $id);

        return response()->json('Successfully Attendance is Updated!');
    }

    public function destroy($id)
    {
        $delete = $this->attendanceService->trash($id);

        return response()->json('Successfully Attendance is Deleted!');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->attendance_id)) {
            if ($request->action_type == 'move_to_trash') {
                $attendance = $this->attendanceService->bulkTrash($request->attendance_id);

                return response()->json('Attendance are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $attendance = $this->attendanceService->bulkRestore($request->attendance_id);

                return response()->json('Attendance are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $attendance = $this->attendanceService->bulkPermanentDelete($request->attendance_id);

                return response()->json('Attendance are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function permanentDelete($id)
    {
        $attendance = $this->attendanceService->permanentDelete($id);

        return response()->json('Attendance is permanently deleted successfully');
    }

    public function restore($id)
    {
        $attendance = $this->attendanceService->restore($id);

        return response()->json('Attendance restored successfully');
    }
}
