<?php

namespace Modules\HRM\Service;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\HRM\Interface\SalaryListServiceInterface;

class SalaryListService implements SalaryListServiceInterface
{
    public function payrollList($request)
    {
        abort_if(! auth()->user()->can('hrm_payroll_index'), 403, 'Access Forbidden');
        // $isBuyerMode = isset(auth()->user()->buyer_mode) && (auth()->user()->buyer_mode == 1);
        $isBuyerMode = config('app.is_buyer_mode');

        $employee = new Collection();

        $query = DB::connection('hrm')->table('employees')
            ->leftJoin('sections', 'employees.section_id', 'sections.id')
            ->leftJoin('designations', 'employees.designation_id', 'designations.id')
            ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
            ->where('employees.employee_type', 3);

        if ($request->employee_id) {
            $query->where('employees.id', $request->employee_id);
        }

        if ($request->section_id) {
            $query->where('employees.section_id', $request->section_id);
        }

        if ($request->designation_id) {
            $query->where('employees.designation_id', $request->designation_id);
        }

        if ($request->shift_id) {
            $query->where('employees.shift_id', $request->shift_id);
        }

        if ($request->type_status) {
            $query->where('employment_status', $request->type_status);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('employees.joining_date', [$from_date, $to_date]); // Final
        }

        // // if ($request->resign_month) {
        // //     $query->whereMonth('resign_date', $request->resign_month);
        // // }
        // if ($request->type_status == 1) {
        //     $query->where('employees.type_status', $request->type_status);
        // }

        // if ($request->type_status == 2) {
        //     $query->where('employees.type_status', $request->type_status);
        //     $query->whereMonth('resign_date', $request->resign_month);
        // }

        // if ($request->type_status == 3) {
        //     $query->where('employees.type_status', $request->type_status);
        //     $query->whereMonth('left_date', $request->resign_month);
        // }

        // if ($request->resign_month) {
        //     $query->whereMonth('left_date', $request->resign_month);
        // }

        // $employee = $query->select('sections.name', 'employees.*')->get();
        $employee = $query->select('sections.name as section_name', 'designations.name as designation_name', 'shifts.name as shift_name', 'employees.*', 'employees.name as employee_name')->get();

        return $employee;
    }

    public function salaryListRequestFilter($request)
    {
        abort_if(! auth()->user()->can('hrm_payroll_index'), 403, 'Access Forbidden');
        $employees_collection = new Collection();
        if ($request->ajax()) {
            $year = $request->year;
            $_month_number = $request->month;
            $section_id = $request->section_id;
            $days_in_month = cal_days_in_month(CAL_GREGORIAN, $_month_number, $year);
            $_last_day_in_month = date('d-m-Y', strtotime("$days_in_month-$_month_number-$year"));
            $_first_day_in_month = date('d-m-Y', strtotime("01-$_month_number-$year"));
            $month_name = date('F', strtotime($_first_day_in_month));
            $month = $month_name;

            // $isBuyerMode = isset(auth()->user()->buyer_mode) && (auth()->user()->buyer_mode == 1);
            $isBuyerMode = 1;

            // QUERY #1 (Requested/All Active Employees)
            $query = DB::connection('hrm')->table('employees')
                // ->where('duty_type_id', 1) //super admin , admin etc
                // ->where('user_type', 2)
                ->where('joining_date', '<', date('Y-m-d', strtotime($_last_day_in_month)))
                ->leftJoin('sections', 'employees.section_id', 'sections.id')
                ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
                ->leftJoin('grades', 'employees.grade_id', 'grades.id')
                ->leftJoin('leave_applications', 'employees.id', 'leave_applications.employee_id')
                ->leftJoin('designations', 'employees.designation_id', 'designations.id');

            if ($request->section_id) {
                $query->where('employees.section_id', $request->section_id);
            }
            if ($request->shift_id) {
                $query->where('employees.shift_id', $request->shift_id);
            }

            // if ($request->type_status) {
            //     $query->where('users.type_status', trim($request->type_status));
            // }

            if ($request->type_status == 1) {
                $query->where('employees.employment_status', $request->type_status);
            }

            if ($request->type_status == 2) {
                $query->where('employees.employment_status', $request->type_status);
                $query->whereMonth('resign_date', $request->resign_month);
            }

            if ($request->type_status == 3) {
                $query->where('employees.employment_status', $request->type_status);
                $query->whereMonth('left_date', $request->resign_month);
            }
            if ($request->employee_id) {
                $query->where('employees.id', $request->employee_id);
            }
        }

        $employees_collection = $query->select([
            'sections.name as section_name',
            'grades.name as grade_name',
            'grades.medical',
            'grades.food',
            'grades.transport',
            'designations.name as designation_name',
            'employees.id',
            'employees.employee_id',
            'employees.name as employee_name',
            'employees.joining_date',
            'employees.salary',
            'employees.overtime_allowed',
            'employees.mobile_banking_account_number',
            'employees.employment_status',
            'employees.left_date',
            'employees.resign_date',
            'shifts.name as shifts_name',
            'shifts.start_time',
            'shifts.end_time',
        ])->distinct()->orderBy('employee_id')->get();

        return $employees_collection;
    }
}
