<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Attendance;
use Modules\HRM\Interface\LeaveRegisterServiceInterface;

class LeaveRegisterService implements LeaveRegisterServiceInterface
{
    private function leaveBalanceCurrentLaw(): array
    {
        return [
            'sl_opening' => 14,
            'cl_opening' => 10,
            'el_opening' => 0,
        ];
    }

    public function getPresentAndLateStatusCount($employee_id, $year): int
    {
        abort_if(! auth()->user()->can('hrm_leave_register_view'), 403, 'Access Forbidden');

        return Attendance::where('employee_id', $employee_id)->where('year', $year)->whereIn('status', ['Present', 'Late'])->count();
    }

    public function getYearlyLeaveOpening($employee, int $year): mixed
    {
        abort_if(! auth()->user()->can('hrm_leave_register_view'), 403, 'Access Forbidden');
        $leaveLaw = $this->leaveBalanceCurrentLaw();
        $jd = $employee->joining_date;
        $mn = 0;
        if (! (strtotime($jd) < strtotime(date("01-01-$year")))) {
            $mn = date('m', strtotime($employee->joining_date)) - 1;
        }
        $total_month = 12 - $mn;
        $total_sl = (int) ceil(($leaveLaw['sl_opening'] / 12) * $total_month);
        $total_cl = (int) ceil(($leaveLaw['cl_opening'] / 12) * $total_month);
        $total_el = $leaveLaw['el_opening'];

        return [
            'total_sl' => $total_sl,
            'total_cl' => $total_cl,
            'total_el' => $total_el,
        ];
    }
}
