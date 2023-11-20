<?php

namespace Modules\HRM\Service;

use Carbon\Carbon;
use Modules\Core\Utils\BanglaConverter;
use Modules\Core\Utils\IntlUtil;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Interface\ELServiceInterface;
use Modules\HRM\Interface\FinalSettlementServiceInterface;

class FinalSettlementService implements FinalSettlementServiceInterface
{
    private $elService;

    public function __construct(
        private EmployeeService $employeeService,
        private AttendanceService $attendanceService, ELServiceInterface $elService)
    {
        $this->elService = $elService;
    }

    public function settleEmployee(Employee $employee, $submission_date, $approval_date, $stamp): array
    {
        abort_if(! auth()->user()->can('hrm_final_settlement_index'), 403, 'Access Forbidden');
        $employee = $this->employeeService->getById($employee->id);
        $gross_salary = $employee->salary;
        $basic = ($employee->salary - 1850) / 1.5;
        $house_rent = round(($basic * 50) / 100);
        $employee->daily_rate = round($gross_salary / 30);

        $employee->sub_total = floatval($basic) + floatval($employee->medical) + floatval($house_rent) + floatval($employee->food) + floatval($employee->transport);

        // Employee service/job period count
        $_joinDate = Carbon::createFromFormat('Y-m-d', $employee->joining_date);
        $_lastServiceDate = Carbon::createFromFormat('Y-m-d', $approval_date);
        $diff = $_joinDate->diff($_lastServiceDate);

        $employee->basic = $basic;
        $employee->house_rent = $house_rent;
        $employee->serviceYear = $totalServiceYear = $diff->format('%Y');
        $employee->serviceMonth = $diff->format('%m');
        $employee->serviceDay = $diff->format('%d');

        $employee->submission_date = date('d-m-Y', strtotime($submission_date));
        $employee->approval_date = date('d-m-Y', strtotime($approval_date));
        $employee->joining_date = date('d-m-Y', strtotime($employee->joining_date));

        $serviceBenefit = 0;
        if (isset($totalServiceYear) && ($totalServiceYear >= 5 && $totalServiceYear < 10)) {
            $serviceBenefit = round(($basic / 30) * 5 * 14);
        } elseif (isset($totalServiceYear) && ($totalServiceYear >= 10)) {
            $serviceBenefit = round(($basic / 30) * 10 * 30);
        }

        $employee->serviceBenefit = $serviceBenefit;

        $serviceYearsArray = $this->attendanceService->getDistinctServiceYears($employee->id);
        $el_report = $this->elService->getAll_EL($employee->id, $serviceYearsArray);

        $total_payable_days = array_sum(array_column($el_report, 'payable_el'));
        $total_payable_money = array_sum(array_column($el_report, 'net_payable'));

        // Sub-total
        $money_sub_total = floatval($serviceBenefit) + floatval($total_payable_money);

        // Deduction
        $stamp = ($stamp == 1) ? 10 : 0;
        $advanced = 0;

        // grand_total
        $money_grand_total = $money_sub_total - ($stamp + $advanced);
        $money_grand_total_text = $this->textFormat($money_grand_total);
        // $money_grand_total_text = '';

        // In Bengali (Stringify) --> NO CALCULATION HERE
        $el_report = array_map(fn ($item) => $this->bn($item), $el_report);
        $advanced = $advanced === 0 ? '' : $advanced;
        $stamp = $stamp === 0 ? '' : $stamp;

        $employee = $this->convertToBangla($employee);

        $el_full_report = [
            'total_payable_days' => $total_payable_days,
            'total_payable_money' => $total_payable_money,
            'money_sub_total' => $money_sub_total,
            'money_grand_total' => $money_grand_total,
        ];

        $el_full_report = array_map(fn ($item) => $this->bn($this->format($item)), $el_full_report);
        $el_full_report['money_grand_total_text'] = ucwords($money_grand_total_text);
        $el_full_report['el_report'] = $el_report;
        $el_full_report['advanced'] = is_numeric($advanced) ? $this->formatBn($advanced) : $this->bn($advanced);
        $el_full_report['stamp'] = is_numeric($stamp) ? $this->formatBn($stamp) : $this->bn($stamp);

        return compact('employee', 'el_full_report');
    }

    public function convertToBangla($employee)
    {
        $employee->serviceYear = $this->bn($employee->serviceYear);
        $employee->serviceMonth = $this->bn($employee->serviceMonth);
        $employee->serviceDay = $this->bn($employee->serviceDay);

        $employee->basic = $this->bn($this->format($employee->basic));
        $employee->house_rent = $this->bn($this->format($employee->house_rent));
        $employee->medical = $this->bn($this->format($employee->medical));
        $employee->food = $this->bn($this->format($employee->food));
        $employee->transport = $this->bn($this->format($employee->transport));
        $employee->sub_total = $this->bn($this->format($employee->sub_total));
        $employee->salary = $this->bn($this->format($employee->salary));
        $employee->daily_rate = $this->bn($this->format($employee->daily_rate));

        $employee->submission_date = $this->bn($employee->submission_date);
        $employee->approval_date = $this->bn($employee->approval_date);
        $employee->joining_date = $this->bn($employee->joining_date);

        $employee->serviceBenefit = $this->bn($employee->serviceBenefit === 0 ? '' : $employee->serviceBenefit);

        return $employee;
    }

    public function bn($str)
    {
        return BanglaConverter::en2bn($str);
    }

    public function format($number)
    {
        return number_format($number, 0, '.', ',');
    }

    public function formatBn($number)
    {
        return $this->bn(number_format($number, 0, '.', ','));
    }

    public function textFormat($number): string
    {
        if (! extension_loaded('intl')) {
            return '';
        }

        return IntlUtil::textFormat($number);
    }
}
