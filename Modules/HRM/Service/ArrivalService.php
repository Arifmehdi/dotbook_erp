<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Employee;
use Modules\HRM\Enums\EmploymentStatus;
use Modules\HRM\Interface\ArrivalServiceInterface;

class ArrivalService implements ArrivalServiceInterface
{
    public function activeEmployeeFilter($request)
    {
        abort_if(! auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');

        $query = Employee::where('employment_status', EmploymentStatus::Active);

        if ($request->hrm_department_id) {
            $query->where('hrm_department_id', $request->hrm_department_id);
        }
        if ($request->shift_id) {
            $query->where('shift_id', $request->shift_id);
        }
        if ($request->designation_id) {
            $query->where('designation_id', $request->designation_id);
        }
        if ($request->grade_id) {
            $query->where('grade_id', $request->grade_id);
        }
        if ($request->employment_status && null != $request->employment_status) {
            $query->where('employment_status', $request->employment_status);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->whereBetween('joining_date', [$form_date, $to_date]); // Final
        }

        return $query;
    }

    public function getRowCount()
    {
        $count = Employee::all()->count();

        return $count;
    }
}
