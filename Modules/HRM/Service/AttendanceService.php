<?php

namespace Modules\HRM\Service;

use DB;
use Modules\Core\Utils\DateTimeUtils;
use Modules\HRM\Entities\Attendance;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\Holiday;
use Modules\HRM\Entities\Shift;
use Modules\HRM\Entities\ShiftAdjustment;
use Modules\HRM\Enums\EmploymentStatus;
use Modules\HRM\Interface\AttendanceServiceInterface;

class AttendanceService implements AttendanceServiceInterface
{
    protected $is_active = EmploymentStatus::Active;

    public function attendanceEmployee($request)
    {
        abort_if(!auth()->user()->can('hrm_attendance_index') ||
            !auth()->user()->can('hrm_person_wise_attendance_index'), 403, 'Access forbidden');


        $query = Attendance::query()
            ->leftJoin('employees', 'attendances.employee_id', 'employees.id')
            ->leftJoin('hrm_departments', 'employees.hrm_department_id', 'hrm_departments.id')
            ->leftJoin('sections', 'employees.section_id', 'sections.id')
            ->leftJoin('sub_sections', 'employees.sub_section_id', 'sub_sections.id')
            ->leftJoin('designations', 'employees.designation_id', 'designations.id')
            ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
            ->orderBy('attendances.id', 'desc')
            ->orderBy('at_date', 'asc');

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
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->where('clock_in_ts', '>=', $from_date);
            $query->where('clock_out_ts', '<=', $to_date);
            // $query->whereBetween('created_at', [$form_date, $to_date]); // Final
        }

        $attendances = $query->select('attendances.*', 'employees.name as employee_name', 'employees.employee_id as employeeId', 'sections.name as section_name', 'shifts.name as shift_name', 'hrm_departments.name as department_name');

        return $attendances;
    }

    public function all()
    {
        abort_if(!auth()->user()->can('hrm_attendance_index'), 403, 'Access Forbidden');
        $items = Attendance::orderBy('id', 'desc');

        return $items;
    }

    //Get Trashed Item list
    public function getTrashedItem()
    {
        abort_if(!auth()->user()->can('hrm_attendance_index'), 403, 'Access Forbidden');
        $item = Attendance::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    public function store(array $attributes)
    {
        abort_if(!auth()->user()->can('hrm_attendance_create'), 403, 'Access Forbidden');
        foreach ($request->employee_ids as $key => $employee_id) {
            $attendance = Attendance::whereDate('at_date_ts', date('Y-m-d'))
                ->where('employee_id', $employee_id)
                ->orderBy('id', 'desc')
                ->first();
            if ($attendance) {
                $attendance->clock_out = $request->clock_outs[$key];
                if ($request->clock_outs[$key]) {
                    $attendance->clock_out_ts = date('Y-m-d ') . $request->clock_outs[$key];
                    $attendance->manual_entry = 1;
                }
                $attendance->save();
            } else {
                $data = new Attendance();
                $data->employee_id = $employee_id;
                $data->at_date = date('d-m-Y');
                $data->at_date_ts = date('Y-m-d');
                $data->clock_in = $request->clock_ins[$key];
                $data->clock_in_ts = date('Y-m-d ') . $request->clock_ins[$key];
                $data->clock_out = $request->clock_outs[$key];

                if ($request->clock_outs[$key]) {
                    $data->clock_out_ts = date('Y-m-d ') . $request->clock_outs[$key];
                    $data->manual_entry = 1;
                }
                $data->month = date('F');
                $data->year = date('Y');
                $shift = Employee::with(['shift'])
                    ->where('id', '=', $data->employee_id)
                    ->first();
                $data->shift = $shift->shift->name;
                $data->shift_id = $shift->shift->id; // shift_id Foreign key

                if ($shift->shift->late_count) {
                    $office_late_time = strtotime($shift->shift->late_count);
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
    }

    public function find(int $id)
    {
        abort_if(!auth()->user()->can('hrm_attendance_view'), 403, 'Access Forbidden');
        $item = Attendance::findOrFail($id);

        return $item;
    }

    public function findBySectionId(int $section_id)
    {
        abort_if(!auth()->user()->can('hrm_attendance_view'), 403, 'Access Forbidden');
        $data = Employee::where('section_id', $section_id);

        return $data;
    }

    public function update(array $request, int $id)
    {
        abort_if(!auth()->user()->can('hrm_attendance_update'), 403, 'Access forbidden');

        $updateAttendance = DB::connection('hrm')->table('attendances')->where('id', $id)->first();
        if ($updateAttendance) {
            $updateAttendance->at_date_ts = date('Y-m-d ', strtotime($updateAttendance->at_date)) . '00:00:00';
            $updateAttendance->clock_in = $request['clock_in'];
            $updateAttendance->clock_in_ts = date('Y-m-d ', strtotime($updateAttendance->at_date)) . $request['clock_in'];

            $shift = Employee::with(['shift'])->where('id', '=', $updateAttendance->employee_id)->first();

            if ($shift->shift->late_count) {
                $office_late_time = strtotime($shift->shift->late_count);
                $ci_time = strtotime($request['clock_in']);
                if ($ci_time > $office_late_time) {
                    $updateAttendance->status = 'Late';
                } else {
                    $updateAttendance->status = 'Present';
                }
            }
            if ($request['clock_out']) {
                if ($updateAttendance->clock_out) {
                    $updateAttendance->clock_in_ts = $request['clock_in_ts'] . ' ' . $request['clock_in'];
                    $updateAttendance->clock_in = $request['clock_in'];
                    $updateAttendance->clock_out_ts = $request['clock_out_ts'] . ' ' . $request['clock_out'];
                    $updateAttendance->clock_out = $request['clock_out'];
                } else {
                    $updateAttendance->clock_in_ts = $request['clock_in_ts'] . ' ' . $request['clock_in'];
                    $updateAttendance->clock_in = $request['clock_in'];
                    $updateAttendance->clock_out_ts = $request['clock_out_ts'] . ' ' . $request['clock_out'];
                    $updateAttendance->clock_out = $request['clock_out'];
                }
            }
            $updateAttendance->manual_entry = 1;
            $updateAttendanceData = DB::connection('hrm')->table('attendances')->where('id', $id)->update((array) $updateAttendance);

            return $updateAttendanceData;
        }
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(!auth()->user()->can('hrm_attendance_delete'), 403, 'Access forbidden');
        $item = Attendance::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_attendance_delete'), 403, 'Access forbidden');
        foreach ($ids as $id) {
            $item = Attendance::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(!auth()->user()->can('hrm_attendance_delete'), 403, 'Access forbidden');
        $item = Attendance::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_attendance_delete'), 403, 'Access forbidden');
        foreach ($ids as $id) {
            $item = Attendance::onlyTrashed()->findOrFail($id);
            $item->forceDelete($item);
        }
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(!auth()->user()->can('hrm_attendance_index'), 403, 'Access forbidden');
        $item = Attendance::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_attendance_index'), 403, 'Access forbidden');
        foreach ($ids as $id) {
            $item = Attendance::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(!auth()->user()->can('hrm_attendance_index'), 403, 'Access forbidden');
        $count = Attendance::count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(!auth()->user()->can('hrm_attendance_index'), 403, 'Access forbidden');
        $count = Attendance::onlyTrashed()->count();

        return $count;
    }

    public function sectionWiseAttendanceStore($request)
    {
        abort_if(!auth()->user()->can('hrm_section_wise_attendance_store'), 403, 'Access forbidden');
        $fallback_shifts = Shift::all();
        $shift_adjustments_collection = ShiftAdjustment::all();
        $employees = Employee::whereIn('id', $request->employee_ids)->get();

        $reqDate = $request->today ?? date('d-m-Y');
        $date = date('d-m-Y', strtotime($reqDate));

        foreach ($request->employee_ids as $key => $employee_id) {
            $updateAttendance = Attendance::where('employee_id', $employee_id)
                ->where('at_date', $date)
                ->first();
            if (isset($updateAttendance)) {
                $updateAttendance->clock_out = $request->clock_outs[$key];
                if (isset($request->clock_ins[$key])) {
                    $updateAttendance->clock_in_ts = date('Y-m-d H:i:s', strtotime("$date {$request->clock_ins[$key]}"));
                }
                if (isset($request->clock_outs[$key])) {
                    $updateAttendance->clock_out_ts = date('Y-m-d H:i:s', strtotime("$date {$request->clock_outs[$key]}"));
                }
                $updateAttendance->manual_entry = 1;
                $updateAttendance->save();
            } else {
                $data = new Attendance();
                $data->employee_id = $employee_id;
                $data->at_date = $date;
                $data->at_date_ts = date('Y-m-d H:i:s', strtotime($date));
                $data->clock_in = $request->clock_ins[$key];
                $data->clock_in_ts = date('Y-m-d H:i:s', strtotime("$date {$data->clock_in}"));
                $data->clock_out = isset($request->clock_outs[$key]) ? $request->clock_outs[$key] : null;
                if (isset($request->clock_outs[$key])) {
                    $data->clock_out_ts = date('Y-m-d H:i:s', strtotime("$date {$data->clock_out}"));
                }
                $data->manual_entry = 1;
                $data->month = date('F');
                $data->year = date('Y');
                $employee = $employees->where('id', $employee_id)->first();

                $data->shift_id = $employee->shift_id;

                // Appropriate shift detection and setup consideration
                $appliedShift = $shift_adjustments_collection
                    ->where('shift_id', $employee->shift_id)
                    ->where('applied_date_from', '<=', $date)
                    ->where('applied_date_to', '>=', $date)
                    ->first();

                if (!isset($appliedShift)) {
                    $appliedShift = $fallback_shifts->where('id', $employee->shift_id)->first();
                }

                if ($appliedShift->late_count) {
                    $office_late_time = strtotime($appliedShift->late_count);
                    $ci_time = strtotime($data['clock_in_ts']);
                    if ($ci_time > $office_late_time) {
                        $data->status = 'Late';
                    } else {
                        $data->status = 'Present';
                    }
                }
                $data->save();
            }
        }
    }

    public function absentAttendanceReport($request)
    {
        abort_if(!auth()->user()->can('hrm_absent_report'), 403, 'Access forbidden');
        $date = $request->at_date ? date('d-m-Y', strtotime($request->at_date)) : date('d-m-Y');
        $attendances_on_date = Attendance::where('at_date', $date)->pluck('employee_id')->toArray();
        $query = Employee::where('duty_type_id', 1) //super admin , admin etc
            ->where('employment_status', 1) // active employee
            ->where('joining_date', '<=', $request->at_date);
        if ($request->section_id) {
            $query->where('section_id', $request->section_id);
        }
        $all_employees_id = $query->pluck('id')->toArray();
        $absent_users_array = array_diff($all_employees_id, $attendances_on_date);

        $query = DB::connection('hrm')->table('employees')
            ->leftJoin('sections', 'employees.section_id', 'sections.id')
            ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
            ->leftJoin('designations', 'employees.designation_id', 'designations.id')
            ->select(
                'employees.name as employee_name',
                'employees.employee_id',
                'employees.joining_date',
                'employees.phone',
                'shifts.name as shift_name',
                'designations.name as designation_name',
                'sections.name as section_name',
            );
        $absent_users = $query->whereIn('employees.id', $absent_users_array);

        return $absent_users;
    }

    public function DateRangeAbsenceCheckerData($request)
    {
        abort_if(!auth()->user()->can('hrm_date_range_absent_checker'), 403, 'Access forbidden');
        if ($request->ajax()) {
            $from = $request->from;
            $to = $request->to;
            $input_date_array = DateTimeUtils::dateRange($from, $to, 'd-m-Y'); //get full month date
            // $holidays_array = Holiday::where('is_buyer_mode',config('is_buyer_mode'))
            $holidays_array = Holiday::where('is_buyer_mode', config('app.is_buyer_mode'))
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
                // ->where('joining_date', '<=', $date)
                ->where('employment_status', 1) // active employee
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

                // $total_present = DB::connection('hrm')->table('attendances')
                $total_present = Attendance::where('employee_id', $employee->id)
                    ->whereIn('at_date', $dates_to_check)
                    ->whereIn('status', ['Present', 'Late'])
                    ->distinct('at_date')
                    ->count();

                $total_leaves = Attendance::where('employee_id', $employee->id)
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

    public function getDistinctServiceYears(int $id): iterable
    {
        abort_if(!auth()->user()->can('hrm_date_range_absent_checker'), 403, 'Access forbidden');

        return DB::connection('hrm')->table('attendances')
            ->where('employee_id', $id)
            ->select('year')
            ->distinct()
            ->pluck('year')
            ->toArray();
    }

    public function getAttendanceLogPaginated($request)
    {
        $attendances = $this->attendanceEmployee($request)->paginate();

        return $attendances;
    }
}
