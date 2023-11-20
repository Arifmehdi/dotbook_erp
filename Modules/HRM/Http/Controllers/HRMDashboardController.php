<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Modules\Core\Utils\DateTimeUtils;
use Modules\HRM\Entities\Attendance;
use Modules\HRM\Entities\Award;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\Notice;

class HRMDashboardController extends Controller
{
    public function hrmDashboard()
    {
        abort_if(!auth()->user()->can('hrm_dashboard'), 403, 'Access Forbidden');
        $dt = date('Y-m-d');
        $date = date('d-m-Y');
        $getMonths = DB::connection('hrm')->table('leave_applications')
            ->whereRaw('"' . $dt . '" between `from_date` and `to_date`')
            ->get();

        $notice_object = Notice::where('deleted_at', null)
            ->orderByDesc('id')->limit(5)
            ->get(['id', 'title', 'description', 'attachment', 'notice_by', 'is_active', 'created_at']);

        $award_object = Award::where('deleted_at', null)->orderByDesc('id')->limit(5)->get(['id', 'employee_id', 'gift_item', 'award_name', 'award_by', 'date', 'month', 'year', 'created_at']);

        $employee_object = Employee::active()
            ->with('section')
            ->where('deleted_at', null)
            ->orderByDesc('id')
            ->select('id', 'employee_id', 'name', 'section_id', 'phone', 'present_village', 'gender', 'joining_date', 'religion');

        $all_employee_object = Employee::get(['id', 'employment_status'])->pluck('employment_status');

        $all_employee_active = $all_employee_object->filter(function ($all_employee) {
            return $all_employee === 1;
        })->count();

        $all_employee_resigned = $all_employee_object->filter(function ($all_employee) {
            return $all_employee === 2;
        })->count();

        $all_employee_left = $all_employee_object->filter(function ($all_employee) {
            return $all_employee === 3;
        })->count();

        $all_employee_terminated = $all_employee_object->filter(function ($all_employee) {
            return $all_employee === 0;
        })->count();

        $all_employee_delete = $all_employee_object->filter(function ($all_employee) {
            return $all_employee === 0;
        })->count();

        $employee_number = $employee_object->pluck('id')->count();

        $employee_gender_object = $employee_object->pluck('gender');

        $employee_male = $employee_gender_object->filter(function ($gen) {
            return $gen === 'Male';
        })->count();

        $employee_female = $employee_gender_object->filter(function ($gen) {
            return $gen === 'Female';
        })->count();

        $employee_others = $employee_gender_object->filter(function ($gen) {
            return $gen === 'Other';
        })->count();

        $employee_not_defined = $employee_gender_object->filter(function ($gen) {
            return $gen === null;
        })->count();

        $employee_religion_object = $employee_object->pluck('religion');

        $employee_muslim = $employee_religion_object->filter(function ($religious) {
            return $religious === 'Muslim';
        })->count();

        $employee_hindu = $employee_religion_object->filter(function ($religious) {
            return $religious === 'Hindu';
        })->count();

        $employee_buddhist = $employee_religion_object->filter(function ($religious) {
            return $religious === 'Buddha';
        })->count();

        $employee_christian = $employee_religion_object->filter(function ($religious) {
            return $religious === 'Christian';
        })->count();

        $employee_religion_other = $employee_religion_object->filter(function ($religious) {
            return $religious === 'Others';
        })->count();

        $employee_religion_not_define = $employee_religion_object->filter(function ($religious) {
            return $religious === null;
        })->count();

        $attendances_object = Attendance::where('at_date', $date)->get(['id', 'employee_id', 'at_date', 'clock_in', 'clock_out', 'clock_in_ts', 'clock_out_ts', 'shift_id', 'status']);

        $today = today();
        $first_day = $today->subYear(1)->firstOfYear();
        $joining_employee = $employee_object->whereBetween('joining_date', [$first_day, $today])->get();

        $monthly_joining = $joining_employee->pluck('joining_date');

        $month_wise_joining = $monthly_joining->filter(function ($join) {
            return date('M', strtotime($join));
        });

        $array_month = [];
        foreach ($joining_employee as $month) {
            $dateTime = new DateTime();
            $dateTime->setDate(1, $month->month_joined, 1);
            $month_short_name = $dateTime->format('M');
            $array_month[$month_short_name] = $month->total_joining;
        }

        $attendances_number = $attendances_object->count();

        $attendances_on_date = $attendances_object->pluck('employee_id')->toArray();

        $all_employees_id = $employee_object->pluck('id')->toArray();

        $all_yearly_attendance =
            $absent_users_array = array_udiff($all_employees_id, $attendances_on_date, function (int $a, int $b) {
                return $a - $b;
            });
        $absent_users = $employee_object->whereIn('id', $absent_users_array)
            ->orderBy('employee_id')->get();
        $absent_users_count = $absent_users->count();

        $employee_count = $employee_object->count();
        $employee_latest = $employee_object->limit(5)->get();
        $present = $employee_number - count($absent_users);
        $leave_count = $getMonths->count();

        $absent_number = $employee_number - ($attendances_number + $leave_count);

        $employees_info = DB::connection('hrm')
            ->table('employees')
            ->leftJoin('leave_applications', 'leave_applications.employee_id', '=', 'employees.id')
            ->leftJoin('hrm_departments', 'hrm_departments.id', '=', 'employees.hrm_department_id')
            ->select(
                'employees.id',
                'employees.employee_id',
                'employees.joining_date',
                'employees.blood',
                'employees.employment_status',
                'employees.gender',
                'employees.religion',
                'employees.hrm_department_id',
                'leave_applications.id as leave_application_id',
                'leave_applications.employee_id as employee_leaveapplications_id',
                'leave_applications.leave_type_id',
                'leave_applications.from_date',
                'leave_applications.to_date',
                'leave_applications.is_paid',
                'leave_applications.approve_day',
                'hrm_departments.name as department_name',
            )
            ->get();
        $department_info = $employees_info->pluck('hrm_department_id')->toArray();
        $blood_info = $employees_info->whereIn('hrm_department_id', $department_info)->pluck('blood')->toArray();

        return view('hrm::hrm_dashboard.home', compact('employee_number', 'attendances_number', 'absent_number', 'leave_count', 'notice_object', 'award_object', 'employee_latest', 'employee_male', 'employee_female', 'employee_others', 'employee_not_defined', 'employee_muslim', 'employee_hindu', 'employee_buddhist', 'employee_christian', 'employee_religion_other', 'employee_religion_not_define', 'all_employee_active', 'all_employee_resigned', 'all_employee_left', 'all_employee_terminated'));
    }
}
