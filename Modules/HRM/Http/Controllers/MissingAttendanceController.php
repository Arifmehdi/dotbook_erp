<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Attendance;
use Modules\HRM\Entities\HrmDepartment;
use Modules\HRM\Entities\Employee;

class MissingAttendanceController extends Controller
{
    public function createMissingAttendance()
    {
        $employees = Employee::get();
        $departments = HrmDepartment::with(['sections'])->get();

        return view('hrm::missing_attendance.missingAttendance', compact('employees', 'departments'));
    }

    public function createPersonWiseMissingAttendance(Request $request)
    {
        if ($request->dataType == 'department') {
            $employee = Employee::where('hrm_department_id', $request->departmentID)->get();
            return $employee;
        } else {
            return 'person ache';
        }
    }

    public function divisionWiseEmployee(Request $request, $id)
    {
        if ($request->dataType == 'department') {
            $employees = Employee::where('section_id', $id)->get();

            return $employees;
        }
        if ($request->dataType == 'section') {
            $employees = Employee::where('id', $id)->get();
            return view('hrm::missing_attendance.partial.row_create_division_wise', compact('employees'));
        }

        if ($request->dataType == 'section-wise') {
            $employee = Employee::find($id);
            return view('hrm::missing_attendance.partial.row_create_division_wise', compact('employee'));
        }
    }

    public function missingAttendanceStore(Request $request)
    {
        if ($request->user_ids == null) {
            return response()->json(['errorMsg' => 'Select employee first for attendance.']);
        }

        foreach ($request->user_ids as $key => $user_id) {

            $updateAttendance = Attendance::whereDate('at_date_ts', date('Y-m-d'))
                ->where('employee_id', $user_id)
                ->orderBy('id', 'desc')
                ->first();
            if ($updateAttendance) {
                $updateAttendance->clock_out = $request->clock_outs[$key];
                if ($request->clock_outs[$key]) {
                    $updateAttendance->clock_out_ts = date('Y-m-d ') . $request->clock_outs[$key];
                    $updateAttendance->manual_entry = 1;
                }
                $updateAttendance->save();
            } else {
                $data = new Attendance();
                $data->employee_id = $user_id;
                $data->at_date = date('d-m-Y ', strtotime($request->start_dates[$key]));
                $data->at_date_ts = date('Y-m-d', strtotime($request->start_dates[$key]));
                $data->clock_in = $request->clock_ins[$key];
                $data->clock_in_ts = date('Y-m-d ', strtotime($request->start_dates[$key])) . $request->clock_ins[$key];
                $data->clock_out = $request->clock_outs[$key];
                $data->clock_out_ts = date('Y-m-d ', strtotime($request->start_dates[$key])) . $request->clock_outs[$key];
                // $data->clock_in_note = $request->clock_in_notes[$key];
                // $data->clock_out_note = $request->clock_out_notes[$key];
                $data->month = date('F');
                $data->year = date('Y');
                // $data->is_completed = 1;
                $data->manual_entry = 1;

                $user_shift = Employee::with('shift')->where('id', $user_id)->first();

                $data->shift = $user_shift->shift_name;
                $data->shift_id = $user_shift->shift->id;
                if ($user_shift->start_time) {
                    $office_late_time = strtotime($user_shift->late_count);
                    $ci_time = strtotime($request->clock_ins[$key]);
                    if ($ci_time > $office_late_time) {
                        $data->status = 'Late';
                    } else {
                        $data->status = 'Present';
                    }
                }
                $data->save();
            }
        }
        session()->flash('success', 'Successfully missing Attendance is Added!');
        return redirect()->route('hrm.persons.index');
    }
}
