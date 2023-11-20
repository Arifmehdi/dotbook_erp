<?php

namespace Modules\HRM\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Interface\AttendanceServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;

class SectionWiseAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    protected $sectionService;

    protected $attendanceService;

    public function __construct(SectionServiceInterface $sectionService, AttendanceServiceInterface $attendanceService)
    {
        $this->sectionService = $sectionService;
        $this->attendanceService = $attendanceService;
    }

    public function create()
    {
        abort_if(!auth()->user()->can('hrm_section_wise_attendance'), 403, 'Access forbidden');
        $departments = $this->sectionService->sectionWithHrmDepartmentAndSelection();

        return view('hrm::section_wise_attendance.create', compact('departments'));
    }

    public function store(Request $request)
    {
        if ($request->employee_ids == null) {
            return response()->json('Select employee first for attendance.', 409);
        }
        $section = $this->attendanceService->sectionWiseAttendanceStore($request);

        return response()->json([
            'message' => 'Successfully Attendance is Added!',
            'next_url' => route('hrm.attendance_log.index'),
        ]);
    }

    public function createSectionWiseRow(Request $request)
    {
        abort_if(!auth()->user()->can('hrm_section_wise_attendance'), 403, 'Access forbidden');
        $section_id = $request->section_id;
        $today = $request->date;
        $employees = Employee::where('section_id', $section_id)->select('id', 'name', 'employee_id')->get();

        return view('hrm::section_wise_attendance.ajax_views.section_row_create', compact('employees', 'today'));
    }
}
