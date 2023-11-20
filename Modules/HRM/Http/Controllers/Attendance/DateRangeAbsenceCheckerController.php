<?php

namespace Modules\HRM\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Utils\DateTimeUtils;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\Holiday;
use Modules\HRM\Interface\AttendanceServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class DateRangeAbsenceCheckerController extends Controller
{
    protected $attendanceService;

    protected $sectionService;

    protected $is_active = 1;

    public function __construct(AttendanceServiceInterface $attendanceService, SectionServiceInterface $sectionService)
    {
        $this->attendanceService = $attendanceService;
        $this->sectionService = $sectionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        // $departments = $this->sectionService->sectionWithHrmDepartmentAndSelection();
        if ($request->ajax()) {
            $absent_employee = $this->attendanceService->DateRangeAbsenceCheckerData($request);
            $rowCount = $absent_employee->count();

            return DataTables::of($absent_employee)
                ->addIndexColumn()
                // ->editColumn('section', function ($row) {
                //     return $row->section_name;
                // })
                // ->editColumn('employee_name', function ($row) {
                //     return $row->employee->name ?? null;
                // })
                // ->editColumn('employee', function ($row) {
                //     return $row->employee->employee_id ?? null;
                // })
                // ->editColumn('shift', function ($row) {
                //     return $row->shift ?? null;
                // })
                // ->editColumn('clock_in_out', function ($row) {
                //     return $row->clock_in ?? null;
                // })
                // ->editColumn('clock_in_out', function ($row) {
                //     return $row->clock_out ?? null;
                // })

                // ->editColumn('created_at', function ($row) {
                //     return $row->created_at->employee_id ?? null;
                // })
                // ->addColumn('action', function ($row) {
                //     $html = '<div class="btn-group" role="group">';
                //     $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                //     $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                //     $html .= '<a class="dropdown-item text-info edit" href="' . route('hrm.persons.edit', $row->id) . '" id="edit"><i class="fa-thin fa-pencil me-1"></i> Edit</a>';
                //     $html .= '<a class="dropdown-item text-warning show" href="' . route('hrm.persons.show', $row->id) . '" id="show"><i class="fa-thin fa-eye me-1"></i> View</a>';
                //     $html .= '</div>';
                //     $html .= '</div>';
                //     return $html;
                // })
                ->rawColumns(['summary'])
                ->with([
                    'allRow' => $rowCount,
                    // 'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::attendance.date_range_absent_checker.create');
    }

    public function absentEmployees(Request $request)
    {
        if ($request->ajax()) {
            $from = $request->from;
            $to = $request->to;
            $input_date_array = DateTimeUtils::dateRange($from, $to, 'd-m-y'); //get full month date
            // $holidays_array = Holiday::where('is_buyer_mode',config('is_buyer_mode'))
            $holidays_array = Holiday::where('is_buyer_mode', $this->is_active)
                ->where('from', '>=', date('Y-m-d', strtotime($from)))
                ->where('to', '<=', date('Y-m-d', strtotime($to)))
                ->orderBy('from')
                ->get()
                ->toArray();

            $holidays_date_array = array_reduce($holidays_array, function ($dates, $holiday) {
                $dates = isset($dates) ? $dates : [];
                $date_range = DateTimeUtils::dateRange($holiday['from'], $holiday['to'], 'd-m-Y');

                return array_merge($dates, $date_range);
            }, []);

            $date = date('Y-m-d');
            $active_employees = DB::connection('hrm')->table('employees')
                // ->where('duty_type_id', 1) //super admin , admin etc
                ->where('employment_status', 1) // active employee
                // ->where('joining_date', '<=', $date)
                ->leftJoin('shifts', 'shifts.id', 'employees.shift_id')
                ->leftJoin('sections', 'sections.id', 'employees.section_id')
                ->select(
                    'employees.id',
                    'employees.employee_id',
                    'employees.name as employee_name',
                    'employees.present_village',
                    'employees.phone',
                    'employees.joining_date',
                    'shifts.name as shifts_name',
                    'sections.name as section_name'
                )
                ->orderBy('employee_id')
                ->get();

            $active_employees = $active_employees->map(function ($employee) use (
                $input_date_array,
                $holidays_date_array,
            ) {
                $joiningDate = isset($employee->joining_date) ? $employee->joining_date : null;

                $input_date_array = array_filter($input_date_array, function ($item) use ($joiningDate) {
                    return strtotime($item) >= strtotime($joiningDate);
                });
                $input_date_array = array_unique($input_date_array);

                $holidays_date_array = array_filter($holidays_date_array, function ($item) use ($joiningDate) {
                    return strtotime($item) >= strtotime($joiningDate);
                });

                $holidays_date_array = array_unique($holidays_date_array);

                $dates_to_check = array_values(array_diff($input_date_array, $holidays_date_array));

                $total_requested_dates = count($input_date_array);
                $total_holidays = count($holidays_date_array);

                $total_present = DB::connection('hrm')->table('attendances')
                    ->where('employee_id', $employee->id)
                    ->whereIn('at_date', $dates_to_check)
                    ->whereIn('status', ['Present', 'Late'])
                    ->distinct('at_date')
                    ->count();

                $total_leaves = DB::connection('hrm')->table('attendances')
                    ->where('employee_id', $employee->id)
                    ->whereIn('at_date', $dates_to_check)
                    ->where('status', 'Leave')
                    ->distinct('at_date')
                    ->count();

                $total_absent = $total_requested_dates - ($total_present + $total_holidays + $total_leaves);

                $employee->shift_and_section = "$employee->shifts_name ($employee->section_name)";
                $employeePhone = isset($employee->phone) ? "($employee->phone)" : '(N/A)';
                $employee->present_address_and_phone = "$employee->present_village $employeePhone";
                // $employee->joining_date = date('d F, Y', strtotime($joiningDate));
                $employee->joining_date = date('Y/m/d', strtotime($joiningDate));

                $employee->summary = "$total_requested_dates  - ($total_present + $total_holidays + $total_leaves ) = <span class=\"text-danger\">$total_absent</span>";

                return $employee;
            });

            return $active_employees;
        }
    }
}
