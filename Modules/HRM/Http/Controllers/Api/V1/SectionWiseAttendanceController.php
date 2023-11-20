<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Attendance;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Interface\AttendanceServiceInterface;
use Modules\HRM\Transformers\SectionWiseAttendanceResource;

class SectionWiseAttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceServiceInterface $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data = $this->attendanceService->attendanceEmployee($request)->paginate();
        $sectionWiseAttendance = SectionWiseAttendanceResource::collection($data);

        return $sectionWiseAttendance;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('hrm::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show(int $section_id)
    {
        $date = date('d-m-Y');
        $employeesIdsBySection = Employee::where('section_id', $section_id)
            ->select('id')
            ->pluck('id')
            ->toArray();
        $attendances = Attendance::where('at_date', $date)
            ->whereIn('employee_id', $employeesIdsBySection)
            ->get();
        $sectionWiseAttendance = SectionWiseAttendanceResource::collection($attendances);

        return $sectionWiseAttendance;

        return view('hrm::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('hrm::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function getAttendanceBySectionAndDate(Request $request)
    {
        // $date = $date ?? date('d-m-Y');
        if (isset($section_id) && $section_id != 'null') {
            $employeesIdsBySection = Employee::where('section_id', $section_id)
                ->select('id')
                ->pluck('id')
                ->toArray();
            $attendances = Attendance::where('at_date', $date)
                ->whereIn('employee_id', $employeesIdsBySection)
                ->get();
            $sectionWiseAttendance = SectionWiseAttendanceResource::collection($attendances);
        } else {
            return response()->json('Please try to better way');
        }

        return $sectionWiseAttendance;

    }
}
