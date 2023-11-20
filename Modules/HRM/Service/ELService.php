<?php

namespace Modules\HRM\Service;

use App\Models\Essential\Todo;
use Illuminate\Support\Facades\DB;
use Modules\HRM\Interface\ELServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\OffDaysServiceInterface;

class ELService implements ELServiceInterface
{
    public $employeeService;

    public $offDaysService;

    public function __construct(EmployeeServiceInterface $employeeService, OffDaysServiceInterface $offDaysService)
    {
        $this->employeeService = $employeeService;
        $this->offDaysService = $offDaysService;
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
        $leave_type_id = DB::connection('hrm')->table('leave_types')->where('name', 'EL')->first()->id ?? 4;

        // EL Enjoyed from LeaveApplication EL Type
        $enjoyed_el = DB::connection('hrm')->table('leave_applications')->where('employee_id', $employeeId)->where('leave_type_id', $leave_type_id)->whereYear('from_date', $year)->get();
        $enjoyed_el_count = $enjoyed_el->sum('approve_day');

        // EL Enjoyed from EL Payments
        $el_payments_collection = DB::connection('hrm')->table('el_payments')->where('employee_id', $employeeId)->where('year', $year);
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
        $offDatesCollection = $this->offDaysService->getByYear($year);
        $offDatesArrayYmd = $offDatesCollection['dates_array'];
        $offDatesArray = array_map(fn ($date) => date('d-m-Y', strtotime($date)), $offDatesArrayYmd);

        // $totalPresentAndLate = DB::connection('hrm')->table('attendances')
        //     ->where('employee_id', $employee_id)
        //     ->where('year', $year)
        //     ->whereIn('status', ['Present', 'Late'])
        //     ->count();

        $total_present_status_in_a_year = DB::connection('hrm')->table('attendances')
            ->where('employee_id', $employeeId)
            ->where('year', $year)
            ->whereNotIn('at_date', $offDatesArray)
            ->whereIn('status', ['Present', 'Late'])
            ->count();

        $yearly_el_count = ceil($total_present_status_in_a_year / 18);

        return [
            'yearly_total_present' => $total_present_status_in_a_year,
            'yearly_el_count' => $yearly_el_count,
        ];
    }

    public function getTotalAttendanceCountExceptOffDays($employeeId, $year)
    {
        $offDays = $this->offDaysService->getByYear($year);
        $offDaysDatesYMD = $offDays['dates_array'];
        $offDaysDatesDMY = array_map(fn ($d) => date('d-m-Y', strtotime($d)), $offDaysDatesYMD);

        return DB::connection('hrm')->table('attendances')
            ->where('employee_id', $employeeId)
            ->where('year', $year)
            ->whereIn('status', ['Present', 'Late'])
            ->whereNotIn('at_date', $offDaysDatesDMY)
            ->count();
    }

    public function getAllOffDaysOfTheYear($year, $format = 'd-m-Y'): array
    {
        abort_if(! auth()->user()->can('hrm_el_calculation_view'), 403, 'Access Forbidden');
        $offDays = $this->offDaysService->getByYear($year);
        $offDaysDatesYMD = $offDays['dates_array'];
        $offDaysDatesDMY = array_map(fn ($d) => date('d-m-Y', strtotime($d)), $offDaysDatesYMD);
        if ($format == 'd-m-Y') {
            return $offDaysDatesDMY;
        } else {
            return $offDaysDatesYMD;
        }
    }

    public function getActiveEmployeeAttendances(array $employeeIds, int $year)
    {
        abort_if(! auth()->user()->can('hrm_el_calculation_view'), 403, 'Access Forbidden');
        $offDaysDatesDMY = $this->getAllOffDaysOfTheYear($year);

        return DB::connection('hrm')->table('attendances')
            ->select(DB::raw('count(*) as total_present, employee_id'))
                // ->where('employee_id', 233)
            ->where('year', $year)
            ->whereIn('employee_id', $employeeIds)
            ->whereIn('status', ['Present', 'Late'])
            ->whereNotIn('at_date', $offDaysDatesDMY)
            ->groupBy('employee_id')
            ->pluck('total_present', 'employee_id')
            ->toArray();
    }

    public function getEL_Calculation($year)
    {
        abort_if(! auth()->user()->can('hrm_el_calculation_view'), 403, 'Access Forbidden');
        $employees = $this->employeeService->activeEmployeesWithOtherInfo(); // Todo:: Apply joining_date filter to employees
        $leave_type_id = DB::connection('hrm')->table('leave_types')->where('name', 'EL')->first()->id ?? 4;
        $leaveapplications = DB::connection('hrm')->table('leave_applications')->where('leave_type_id', $leave_type_id)->whereYear('from_date', $year)->get()->toArray();
        $el_payments = DB::connection('hrm')->table('el_payments')->where('year', $year)->get()->toArray();

        $employeeIds = $employees->pluck('id')->toArray();
        $allRealAttendance = $this->getActiveEmployeeAttendances($employeeIds, $year);
        foreach ($employees as $key => $employee) {
            if (\array_key_exists($employee->id, $allRealAttendance)) {
                $data = $employee->yearly_total_present = $allRealAttendance[$employee->id];
            } else {
                $employee->yearly_total_present = 0;
            }
            $employee->yearly_el_count = ceil($employee->yearly_total_present / 18);
            $employee->enjoyed_el_count = array_reduce($leaveapplications, function ($carry, $item) use ($employee) {
                if ($item->user_id == $employee->id) {
                    return $carry + $item->approve_day;
                }

                return $carry;
            }, 0);

            $employee->el_paid_days = array_reduce($el_payments, function ($carry, $item) use ($employee) {
                if ($item->user_id == $employee->id) {
                    return $carry + $item->el_days;
                }

                return $carry;
            }, 0);

            $employee->taken_el = $employee->enjoyed_el_count + $employee->el_paid_days;

            $employee->payable_el = $employee->yearly_el_count - ($employee->taken_el);

            $employee->daily_remuneration = round($employee->salary / 30);
            $joinDate = Carbon::parse($employee->joining_date);
            $lastDateOfYear = Carbon::parse("$year-01-01")->endOfYear();
            $employee->service_period = $joinDate->diff($lastDateOfYear)->format('%YY, %mM, %dD');
            $employee->net_payable = $employee->daily_remuneration * $employee->payable_el;
            $employee->joining_date = date('Y/m/d', strtotime($employee->joining_date));
        }

        return $employees;
    }
}
