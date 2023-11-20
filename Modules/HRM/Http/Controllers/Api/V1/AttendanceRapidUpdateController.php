<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\Attendance\DateWiseRapidUpdateRequest;
use Modules\HRM\Http\Requests\Attendance\EmployeeWiseRapidUpdateRequest;
use Modules\HRM\Interface\AttendanceRapidUpdateServiceInterface;
use Modules\HRM\Interface\AttendanceServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;

class AttendanceRapidUpdateController extends Controller
{
    protected $attendanceService;

    protected $employeeService;

    private $attendanceRapidUpdateService;

    public function __construct(
        AttendanceServiceInterface $attendanceService,
        EmployeeServiceInterface $employeeService,
        AttendanceRapidUpdateServiceInterface $attendanceRapidUpdateService,
    ) {
        $this->employeeService = $employeeService;
        $this->attendanceService = $attendanceService;
        $this->attendanceRapidUpdateService = $attendanceRapidUpdateService;
    }

    public function dateWiseRapidUpdate(DateWiseRapidUpdateRequest $request)
    {
        $attributes = $request->validated();
        $attendances = $this->attendanceRapidUpdateService->dateWiseRapidUpdate($attributes);

        return $attendances;
    }

    public function employeeWiseRapidUpdate(EmployeeWiseRapidUpdateRequest $request)
    {
        $attributes = $request->validated();
        $attendances = $this->attendanceRapidUpdateService->employeeWiseRapidUpdate($attributes);

        return $attendances;
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
    public function show($id)
    {
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
}
