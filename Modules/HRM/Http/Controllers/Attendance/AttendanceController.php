<?php

namespace Modules\HRM\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\Attendance\UpdateAttendanceRequest;
use Modules\HRM\Interface\AttendanceServiceInterface;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceServiceInterface $attendanceService)
    {
        return $this->attendanceService = $attendanceService;
    }

    public function index(Request $request)
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {

    }

    public function edit($id)
    {

    }

    public function update(UpdateAttendanceRequest $request, $id)
    {

    }

    public function destroy($id)
    {

    }
}
