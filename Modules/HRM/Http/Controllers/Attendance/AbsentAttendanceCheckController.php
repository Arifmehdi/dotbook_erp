<?php

namespace Modules\HRM\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Interface\AttendanceServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class AbsentAttendanceCheckController extends Controller
{
    protected $sectionService;

    protected $attendanceService;

    public function __construct(SectionServiceInterface $sectionService, AttendanceServiceInterface $attendanceService)
    {
        $this->sectionService = $sectionService;
        $this->attendanceService = $attendanceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {

        abort_if(!auth()->user()->can('hrm_absent_report'), 403, 'Access forbidden');
        $date = $request->at_date ? date('d-m-Y', strtotime($request->at_date)) : date('d-m-Y');
        if ($request->ajax()) {
            $absent_users = $this->attendanceService->absentAttendanceReport($request);
            $rowCount = $absent_users->count();

            return DataTables::of($absent_users)
                ->addIndexColumn()
                ->editColumn('date', function ($row) use ($date) {
                    return date('Y/m/d', strtotime($date));
                })
                ->editColumn('joining_date', function ($row) {
                    return date('Y/m/d', strtotime($row->joining_date));
                })
                ->rawColumns(['date'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->make(true);
        }
        $departments = $this->sectionService->sectionWithHrmDepartmentAndSelection();

        return view('hrm::attendance.absent_attendance.index', compact('departments'));
    }

    public function absentReport()
    {
        $departments = $this->sectionService->sectionWithHrmDepartmentAndSelection();

        return view('hrm::attendance.absent_attendance.index', compact('departments'));
    }
}
