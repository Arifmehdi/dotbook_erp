<?php

namespace Modules\HRM\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HRM\Entities\Attendance;
use Modules\HRM\Exports\DailyAttendance;
use Modules\HRM\Interface\AttendanceServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class DailyAttendanceListController extends Controller
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
        abort_if(!auth()->user()->can('hrm_daily_attendance_report_index'), 403, 'Access forbidden');
        try {
            $employees = $this->employeeService->employeeActiveListWithId();
            $attendances = $this->attendanceService->attendanceEmployee($request);
            // $rowCount = $this->attendanceService->getRowCount();
            $rowCount = $attendances->count();
            if ($request->ajax()) {
                return DataTables::of($attendances)
                    ->addIndexColumn()
                    ->editColumn('section', function ($row) {
                        return $row->section_name;
                    })
                    ->editColumn('at_date_format', function ($row) {
                        $at_date_formats = Carbon::parse($row->at_date)->format('Y-m-d');

                        return $at_date_formats ?? '';
                    })
                    ->editColumn('shift', function ($row) {
                        return $row->shift ?? '';
                    })
                    ->editColumn('clock_in', function ($row) {
                        return Carbon::parse($row->clock_in)?->format(config('hrm.time_format')) ?? '';
                    })
                    ->editColumn('clock_out', function ($row) {
                        return Carbon::parse($row->clock_out)?->format(config('hrm.time_format')) ?? '';
                    })

                    ->editColumn('created_at', function ($row) {
                        return $row->created_at->employee_id ?? '';
                    })
                    ->addColumn('action', function ($row) {
                        $html = '<div class="btn-group" role="group">';
                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                        if (auth()->user()->can('hrm_daily_attendance_update')) {
                            $html .= '<a class="dropdown-item edit" href="' . route('hrm.persons.edit', $row->id) . '" id="edit"><i class="fa-thin fa-pencil me-1"></i> Edit</a>';
                        }
                        if (auth()->user()->can('hrm_daily_attendance_view')) {
                            $html .= '<a class="dropdown-item show" href="' . route('hrm.persons.show', $row->id) . '" id="show"><i class="fa-thin fa-eye me-1"></i> View</a>';
                        }
                        $html .= '</div>';
                        $html .= '</div>';

                        return $html;
                    })
                    //                     hrm_daily_attendance_index

                    ->rawColumns(['action', 'section', 'employee', 'employee_name', 'clock_in', 'clock_out', 'shift'])
                    ->with([
                        'allRow' => $rowCount,
                        // 'trashedRow' => $trashedCount,
                    ])
                    ->smart(true)
                    ->make(true);
            }

            return view('hrm::daily-attendance-list.index', compact('employees'));
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }


    // For Excel Download method
    public function exportExcelFile(Request $request)
    {
        abort_if(!auth()->user()->can('hrm_daily_attendance_report_excel_export'), 403, 'Access forbidden');
        $title = 'My Page Title';
        if ($request->ajax()) {
            $attendances = '';
            $attendances = $this->attendanceService->attendanceEmployee($request)
                ->orderBy('attendances.id')
                ->select(
                    'employees.employee_id',
                    'employees.name as employee_name',
                    'sections.name as section_name',
                    'designations.name as designation_name',
                    'attendances.clock_in',
                    'attendances.clock_out',
                    'attendances.shift',
                    'attendances.status',
                );

            return Excel::download(new DailyAttendance($attendances, $title), 'Daily-Attendance-List.xlsx');
        }
    }
    // For Custom print

    public function DailyReportPrint(Request $request)
    {
        if ($request->ajax()) {
            $attendances = '';

            $query = \DB::connection('hrm')->table('attendances')
                ->leftJoin('employees', 'attendances.employee_id', 'employees.id')
                ->leftJoin('hrm_departments', 'employees.hrm_department_id', 'hrm_departments.id')
                ->leftJoin('sections', 'employees.section_id', 'sections.id')
                ->leftJoin('sub_sections', 'employees.sub_section_id', 'sub_sections.id')
                ->leftJoin('designations', 'employees.designation_id', 'designations.id')
                ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
                ->orderBy('id', 'desc');

            if ($request->employee_id) {
                $query->where('attendances.employee_id', $request->employee_id);
            }
            if ($request->hrm_department_id) {
                $query->where('employees.hrm_department_id', $request->hrm_department_id);
            }
            if ($request->section_id) {
                $query->where('employees.section_id', $request->section_id);
            }
            if ($request->sub_section_id) {
                $query->where('employees.sub_section_id', $request->sub_section_id);
            }
            if ($request->designation_id) {
                $query->where('employees.designation_id', $request->designation_id);
            }
            if ($request->shift_id) {
                $query->where('employees.shift_id', $request->shift_id);
            }
            $at_date = '';
            if ($request->date_range) {
                $at_date = $request->date_range;
                $date_range = explode('-', $request->date_range);
                $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
                $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
                $query->where('clock_in_ts', '>=', $from_date);
                $query->where('clock_out_ts', '<=', $to_date);
                // $query->whereBetween('created_at', [$form_date, $to_date]); // Final
            }
            $attendances = $query->select(
                'attendances.*',
                'employees.name',
                'employees.joining_date',
                'employees.employee_id',
                'employees.section_id as division_id',
                'designations.name as position_name',
                'sections.name as division_name',
                'shifts.id as shift_id',
            )
                ->limit(1000)
                ->get();

            return view('hrm::daily-attendance-list.attendance_print', compact('attendances', 'at_date'));
        }
    }
}
