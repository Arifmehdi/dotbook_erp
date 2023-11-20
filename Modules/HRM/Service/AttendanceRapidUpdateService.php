<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Attendance;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\Shift;
use Modules\HRM\Entities\ShiftAdjustment;
use Modules\HRM\Interface\AttendanceRapidUpdateServiceInterface;

class AttendanceRapidUpdateService implements AttendanceRapidUpdateServiceInterface
{
    public function dateWiseRapidUpdate(array $attributes)
    {
        abort_if(!auth()->user()->can('hrm_attendance_rapid_update_date_wise'), 403, 'Access forbidden');
        $date = date('d-m-Y', strtotime($attributes['date']));
        $attendances = Attendance::query()
            ->leftJoin('employees', 'attendances.employee_id', 'employees.id')
            ->select('employees.name', 'employees.employee_id as employeeId', 'attendances.*')
            ->where('attendances.at_date', $date)
            ->get();
        $fallback_shift = Shift::latest()->get();
        $shift_adjustments = ShiftAdjustment::latest()->get();

        $collection = [
            'date' => $date,
            'fallback_shift' => $fallback_shift,
            'shift_adjustments' => $shift_adjustments,
            'attendances' => $attendances,
        ];

        return $collection;
    }

    public function employeeWiseRapidUpdate(array $attributes)
    {
        abort_if(!auth()->user()->can('hrm_attendance_rapid_update_employee_wise'), 403, 'Access forbidden');
        $employee_id = $attributes['employee_id'];
        $month = $attributes['month'];
        $year = $attributes['year'];

        $employee = Employee::find($employee_id);
        $attendance_dates = \Modules\Core\Utils\DateTimeUtils::getMonthDatesAsArray($year, $month, 'd-m-Y');
        $query = Attendance::query()
            ->leftJoin('hrm_departments', 'employees.hrm_department_id', 'hrm_departments.id')
            ->leftJoin('sections', 'employees.section_id', 'sections.id')
            ->leftJoin('sub_sections', 'employees.sub_section_id', 'sub_sections.id')
            ->leftJoin('designations', 'employees.designation_id', 'designations.id')
            ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
            ->where('attendances.employee_id', $employee_id)
            ->orderBy('attendances.id', 'desc')->orderBy('at_date', 'asc');

        $attendances = $query->select('attendances.*');
        $attendance_dates = \Modules\Core\Utils\DateTimeUtils::getMonthDatesAsArray($year, $month, 'd-m-Y');
        $fallback_shifts = Shift::latest()->get();
        $shift_adjustments = ShiftAdjustment::latest()->get();
        $attendances = [
            'employee' => $employee,
            'months' => $month,
            'year' => $year,
            'attendances' => $attendances,
            'attendance_dates' => $attendance_dates,
            'fallback_shifts' => $fallback_shifts,
            'shift_adjustments' => $shift_adjustments,
        ];

        return $attendances;
    }
}
