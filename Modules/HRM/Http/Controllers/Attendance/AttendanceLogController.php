<?php

namespace Modules\HRM\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\HRM\Interface\AttendanceServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class AttendanceLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    protected $attendanceService;

    protected $employeeService;

    public function __construct(AttendanceServiceInterface $attendanceService, EmployeeServiceInterface $employeeService)
    {
        $this->attendanceService = $attendanceService;
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {
        abort_if(! auth()->user()->can('hrm_attendance_log_index'), 403, 'Access forbidden');
        $employees = $this->employeeService->employeeActiveListWithId();
        $attendances = $this->attendanceService->attendanceEmployee($request);

        $rowCount = $attendances->count();

        if ($request->ajax()) {
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

                ->editColumn('clock_in', function ($row) {
                    return Carbon::parse($row->clock_in)?->format(config('hrm.time_format')) ?? '';
                })
                ->editColumn('clock_out', function ($row) {
                    $clock_out = isset($row->clock_out) ? Carbon::parse($row->clock_out)->format(config('hrm.time_format')) : '<span class="d-block text-center"> -- </span>';
                    return $clock_out;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->employee_id ?? null;
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->can('hrm_attendance_log_update')) {
                        $html .= '<a class="dropdown-item edit" href="'.route('hrm.persons.edit', $row->id).'" id="edit"><i class="fa-thin fa-pencil me-1"></i> Edit</a>';
                    }
                    if (auth()->user()->can('hrm_attendance_log_view')) {
                        $html .= '<a class="dropdown-item show" href="'.route('hrm.persons.show', $row->id).'" id="show"><i class="fa-thin fa-eye me-1"></i> View</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;

                })
                ->rawColumns(['action', 'section',  'clock_in', 'clock_out', 'at_date_format'])
                ->with([
                    'allRow' => $rowCount,
                    // 'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::attendance-log.index', compact('employees'));
    }
}
