<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\LeaveApplication;
use Modules\HRM\Interface\LeaveServiceInterface;

class LeaveService implements LeaveServiceInterface
{
    public function getAllLeavesByYear(int|string $year): iterable
    {
        abort_if(! auth()->user()->can('hrm_leave_view'), 403, 'Access Forbidden');

        return LeaveApplication::query()
            ->where('year', $year)
            ->get();
    }

    public function getAllLeavesByMonthYear(string $month, int|string $year): iterable
    {
        abort_if(! auth()->user()->can('hrm_leave_view'), 403, 'Access Forbidden');

        return LeaveApplication::query()
            ->where('year', $year)
            ->where('month', $month)
            ->get();
    }

    public function getLeavesByIdAndYear(int|string $id, int|string $year): iterable
    {
        abort_if(! auth()->user()->can('hrm_leave_view'), 403, 'Access Forbidden');
        $leaveApplication = LeaveApplication::query()
            ->leftJoin('leave_types', 'leave_types.id', 'leave_applications.leave_type_id')
            ->where('leave_applications.employee_id', $id)
            ->where('from_date', $year)
            ->select('leave_applications.*', 'leave_types.name', 'leave_types.days')
            ->get()
            ->toArray();

        return $leaveApplication;

    }

    public function getLeavesByEmployeeIdAndYear(int|string $employee_id, int|string $year): iterable
    {
        abort_if(! auth()->user()->can('hrm_leave_view'), 403, 'Access Forbidden');
        $employee = Employee::where('employee_id', $employee_id)->first();

        return LeaveApplication::query()
            ->leftJoin('leavetype', 'leavetype.id', 'leaveapplications.type_id')
            ->where('leaveapplications.user_id', $employee->id)
            ->where('year', $year)
            ->select('leaveapplications.*', 'leavetype.type_name', 'leavetype.days')
            ->get()
            ->toArray();
    }

    public function getLeavesByIdAndMonthYear(int|string $id, string $month, int|string $year): iterable
    {
        abort_if(! auth()->user()->can('hrm_leave_view'), 403, 'Access Forbidden');

        return LeaveApplication::query()
            ->leftJoin('leavetype', 'leavetype.id', 'leaveapplications.type_id')
            ->where('leaveapplications.user_id', $id)
            ->where('year', $year)
            ->where('month', $month)
            ->select('leaveapplications.*', 'leavetype.type_name', 'leavetype.days')
            ->get();
    }

    public function getLeavesByEmployeeIdAndMonthYear(int|string $employee_id, string $month, int|string $year): iterable
    {
        abort_if(! auth()->user()->can('hrm_leave_view'), 403, 'Access Forbidden');
        $employee = Employee::where('employee_id', $employee_id)->first();

        return LeaveApplication::query()
            ->where('user_id', $employee->id)
            ->where('year', $year)
            ->where('month', $month)
            ->get();
    }

    public function getTypeWiseYearlyLeaves($employee_id, $year): iterable
    {
        abort_if(! auth()->user()->can('hrm_leave_view'), 403, 'Access Forbidden');
        $employees_yearly_leaves = $this->getLeavesByIdAndYear($employee_id, $year);

        return array_reduce($employees_yearly_leaves, function ($arr, $item) {
            $leave_type = $item->type_name;
            switch ($leave_type) {
                case 'CL':
                    array_push($arr['cl'], $item);
                    break;
                case 'SL':
                    array_push($arr['sl'], $item);
                    break;
                case 'ML':
                    array_push($arr['ml'], $item);
                    break;
                case 'EL':
                    array_push($arr['el'], $item);
                    break;
                default:
                    break;
            }

            return $arr;
        }, [
            'cl' => [],
            'sl' => [],
            'ml' => [],
            'el' => [],
        ]);
    }
}
