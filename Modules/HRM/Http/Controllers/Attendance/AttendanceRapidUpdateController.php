<?php

namespace Modules\HRM\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Utils\DateTimeUtils;
use Modules\HRM\Entities\Attendance;
use Modules\HRM\Entities\Shift;
use Modules\HRM\Entities\ShiftAdjustment;
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

    public function attendanceRapidUpdate()
    {
        abort_if(! auth()->user()->can('hrm_attendance_rapid_update'), 403, 'Access forbidden');
        $months = DateTimeUtils::months_array();
        $years = DateTimeUtils::years_array();
        $employees = $this->employeeService->employeeActiveListWithId();

        return view('hrm::attendance-rapid-update.index', compact('employees', 'months', 'years'));
    }

    public function dateWiseRapidUpdate(DateWiseRapidUpdateRequest $request)
    {
        $attributes = $request->validated();
        $attendances = $this->attendanceRapidUpdateService->dateWiseRapidUpdate($attributes);

        return view('hrm::attendance-rapid-update.date-wise-adjustment', compact('attendances'));
    }

    public function employeeWiseRapidUpdate(EmployeeWiseRapidUpdateRequest $request)
    {
        $attributes = $request->validated();
        $attendances = $this->attendanceRapidUpdateService->employeeWiseRapidUpdate($attributes);

        return view('hrm::attendance-rapid-update.employee-wise-adjustment', compact('attendances'));
    }

    //shift change attendance adjustment
    public function ShiftAdjustment($id, $shift)
    {
        Attendance::where('id', $id)->update(['shift' => $shift]);

        return response()->json('Updated Successfully');
    }

    //shift insert if attendance not exist
    public function MissingShiftAdjustment($id, $at_date, $shift)
    {
        $check = Attendance::where('employee_id', $id)->where('at_date', $at_date)->first();
        if ($check) {
            return response()->json('attendance already exist');
        } else {
            $data = [];
            $data['employee_id'] = $id;
            $data['at_date'] = $at_date;
            $data['at_date_ts'] = date('Y-m-d', strtotime($at_date));
            $data['month'] = date('F', strtotime($at_date));
            $data['year'] = date('Y', strtotime($at_date));
            $data['shift'] = $shift;
            Attendance::insert($data);

            return response()->json('Shift Change Successful!');
        }
    }

    //clock in empty
    public function clockInEmpty($id)
    {
        Attendance::where('id', $id)->update(['clock_in' => null, 'clock_in_ts' => null]);

        return response()->json('Updated Successfully');
    }

    //clock In change
    public function clockInAdjustment($id, $clock_in)
    {
        $attendance = Attendance::find($id);
        $defaultShift = Shift::where('name', $attendance->shift)->first();
        $shift = ShiftAdjustment::where('shift_id', $defaultShift->id)
            ->where('applied_date_from', '<=', date('Y-m-d', strtotime($attendance->at_date)))
            ->where('applied_date_to', '>=', date('Y-m-d', strtotime($attendance->at_date)))
            ->first();

        $data = [];
        $data['clock_in'] = $clock_in;
        $data['clock_in_ts'] = date('Y-m-d', strtotime($attendance->at_date)).' '.$clock_in.':00';

        $data['bm_clock_in'] = $data['clock_in'];
        $data['bm_clock_in_ts'] = $data['clock_in_ts'];

        $data['manual_entry'] = 1;
        //attendance late or present
        if (isset($shift)) {
            if (strtotime($clock_in) <= strtotime($shift->late_count)) {
                $data['status'] = 'Present';
            } else {
                $data['status'] = 'Late';
            }
        }
        $attendance->update($data);
        $result = [
            'message' => 'Updated Successfully',
            'status' => $attendance->status,
            'date' => $attendance->at_date->format('d-m-Y'),
        ];

        return response()->json($result);
    }

    //clock out
    public function clockOutAdjustment($id, $clock_out)
    {
        $attendance = Attendance::find($id);
        $clock_out_date = date('Y-m-d', strtotime($clock_out));
        $clock_out = date('H:i', strtotime($clock_out));
        $clock_out_ts = $clock_out_date.' '.$clock_out;

        $data = [];
        $data['clock_out'] = $clock_out;
        $data['clock_out_ts'] = $clock_out_ts.':00';
        $data['bm_clock_out'] = $data['clock_out'];
        $data['bm_clock_out_ts'] = $data['clock_out_ts'];
        $data['manual_entry'] = 1;
        $attendance->update($data);
        $result = [
            'message' => 'Updated Successfully',
            'date' => $attendance->at_date->format('d-m-Y'),
        ];

        return response()->json($result);
    }

    //clock-Out-ts adjustment
    public function clockOutTsAdjustment($id, $clock_out_ts)
    {
        $clock_out_date = date('Y-m-d', strtotime($clock_out_ts));
        $clock_out = date('H:i', strtotime($clock_out_ts));
        $clock_out_ts = $clock_out_date.' '.$clock_out;

        $data = [];
        $data['clock_out'] = $clock_out;
        $data['clock_out_ts'] = $clock_out_ts.':00';

        $data['bm_clock_out'] = $data['clock_out'];
        $data['bm_clock_out_ts'] = $data['clock_out_ts'];

        $data['manual_entry'] = 1;
        $data['status'] = 'Present';
        $attendance = Attendance::where('id', $id)->update($data);
        $result = [
            'message' => 'Updated Successfully',
            'status' => $attendance->status,
            'date' => $attendance->at_date->format('d-m-Y'),
        ];

        return response()->json($result);
    }

    public function clockOutEmpty($id)
    {
        Attendance::where('id', $id)->update(['clock_out' => null, 'clock_out_ts' => null]);

        return response()->json('Updated Successfully');
    }

    //adjustment delete
    public function AdjustmentAttDelete($id)
    {
        Attendance::where('id', $id)->delete();

        return response()->json('Successfully Deleted!');
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
