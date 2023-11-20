<?php

namespace Modules\HRM\Service;

use DB;
use Modules\HRM\Interface\ELCalculationServiceInterface;

class ELCalculationService implements ELCalculationServiceInterface
{
    public function __construct(private EmployeeService $employeeService)
    {
    }

    public function getAll_EL(int $employeeId, array $years): array
    {
        abort_if(! auth()->user()->can('hrm_el_calculation_index'), 403, 'Access Forbidden');
        $el_report = [];
        foreach ($years as $year) {
            $el_report[$year] = $this->getYearlyELInDetail($employeeId, $year);
        }

        return $el_report;
    }

    public function getYearlyELInDetail(int $employeeId, int $year): array
    {
        abort_if(! auth()->user()->can('hrm_el_calculation_view'), 403, 'Access Forbidden');
        $leave_type_id = DB::connection('hrm')
            ->table('leave_types')
            ->where('name', 'EL')
            ->first()->id ?? 4;

        // EL Enjoyed from LeaveApplication EL Type
        $enjoyed_el = DB::connection('hrm')
            ->table('leave_applications')
            ->where('employee_id', $employeeId)
            ->where('leave_type_id', $leave_type_id)
            ->whereYear('from_date', $year)
            ->get();

        $enjoyed_el_count = $enjoyed_el->sum('approve_day');

        // EL Enjoyed from EL Payments
        $el_payments_collection = DB::connection('hrm')
            ->table('el_payments')
            ->where('employee_id', $employeeId)
            ->where('year', $year);
        $el_paid_days = $el_payments_collection->sum('el_days');

        ['yearly_el_count' => $yearly_el_count, 'yearly_total_present' => $yearly_total_present] = $this->getYearlyEL($employeeId, $year);

        $payable_el = $yearly_el_count - ($enjoyed_el_count + $el_paid_days);
        $daily_remuneration = $this->employeeService->getEmployee_DailyRemuneration($employeeId);
        $net_payable = $daily_remuneration * $payable_el;

        return [
            'year' => $year,
            'yearly_total_present_status' => $yearly_total_present,
            'yearly_el_count' => $yearly_el_count,
            'enjoyed_el_count' => $enjoyed_el_count,
            'el_paid_days' => $el_paid_days,
            'payable_el' => $payable_el,
            'daily_remuneration' => $daily_remuneration,
            'net_payable' => $net_payable,
        ];
    }

    public function getYearlyEL(int $employeeId, int $year): array
    {
        abort_if(! auth()->user()->can('hrm_el_calculation_view'), 403, 'Access Forbidden');
        $total_present_status_in_a_year = DB::connection('hrm')->table('attendances')
            ->where('employee_id', $employeeId)
            ->where('year', $year)
            ->whereIn('status', ['Present', 'Late'])
            ->count();

        $yearly_el_count = ceil($total_present_status_in_a_year / 18);

        return [
            'yearly_total_present' => $total_present_status_in_a_year,
            'yearly_el_count' => $yearly_el_count,
        ];
    }

    public function getEL_Calculation(int $year): iterable
    {
        abort_if(! auth()->user()->can('hrm_el_calculation_index'), 403, 'Access Forbidden');
        $employees = $this->employeeService->activeEmployeesWithOtherInfo();
        $attendances = DB::connection('hrm')->table('attendances')
            ->where('year', $year)
            ->whereIn('status', ['Present', 'Late'])
            ->get()
            ->toArray();
        $leave_type_id = DB::connection('hrm')->table('leave_types')
            ->where('name', 'EL')
            ->first()->id ?? 4;
        $leaveapplications = DB::connection('hrm')->table('leave_applications')
            ->where('leave_type_id', $leave_type_id)
            ->whereYear('from_date', $year)
            ->get()->toArray();
        $el_payments = DB::connection('hrm')->table('el_payments')
            ->where('year', $year)
            ->get()->toArray();

        foreach ($employees as $key => $employee) {
            $employee->yearly_total_present = array_reduce($attendances, function ($carry, $item) use ($employee) {
                if ($item->employee_id == $employee->id) {
                    return $carry + 1;
                }

                return $carry;
            }, 0);

            $employee->yearly_el_count = ceil($employee->yearly_total_present / 18);
            $employee->enjoyed_el_count = array_reduce($leaveapplications, function ($carry, $item) use ($employee) {
                if ($item->employee_id == $employee->id) {
                    return $carry + $item->approve_day;
                }

                return $carry;
            }, 0);

            $employee->el_paid_days = \array_reduce($el_payments, function ($carry, $item) use ($employee) {
                if ($item->employee_id == $employee->id) {
                    return $carry + $item->el_days;
                }

                return $carry;
            }, 0);

            $employee->taken_el = $employee->enjoyed_el_count + $employee->el_paid_days;

            $employee->payable_el = $employee->yearly_el_count - ($employee->taken_el);

            $employee->daily_remuneration = round($employee->salary / 30);

            $employee->net_payable = $employee->daily_remuneration * $employee->payable_el;
        }

        return $employees;
    }
}
