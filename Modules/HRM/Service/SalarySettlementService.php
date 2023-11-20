<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\SalarySettlement;
use Modules\HRM\Interface\SalarySettlementServiceInterface;

class SalarySettlementService implements SalarySettlementServiceInterface
{
    public function store(array $settlement)
    {
        abort_if(! auth()->user()->can('hrm_salary_settlement_create'), 403, 'Access Forbidden');

        $employeeId = $settlement['employee_id'];
        $employee = Employee::where('id', $employeeId)->first();

        $how_much_amount = $settlement['how_much_amount'];
        $isIncrement = $settlement['salary_type'] == 1; // Increment
        $isDecrement = $settlement['salary_type'] == 2; // Decrement
        $isFixed = $settlement['amount_type'] == 1;  // Percentage otherwise
        $isPercentageOnBasic = $settlement['amount_type'] == 2;  // Percentage otherwise
        $isPercentageOnGross = $settlement['amount_type'] == 3;  // Percentage otherwise

        $employeeCurrentSalary = $employee->salary;
        $employeeCurrentGrossSalary = $employee->grossSalary;
        $beneficialSalary = $employee->beneficialSalary;
        if ($isFixed && $isIncrement) {
            $totalAmount = $how_much_amount + $employeeCurrentSalary;
        }
        if ($isFixed && $isDecrement) {
            $totalAmount = $employeeCurrentSalary - $how_much_amount;
        }

        if ($isPercentageOnBasic) {
            if ($isDecrement) {
                $percentageAmount = $employeeCurrentSalary * $how_much_amount / 100;
                $totalAmount = $employeeCurrentSalary - $percentageAmount;
            }
        }
        if ($isPercentageOnBasic) {
            if ($isIncrement) {
                $percentageAmount = $employeeCurrentSalary * $how_much_amount / 100;
                $totalAmount = $percentageAmount + $employeeCurrentSalary;
            }
        }
        if ($isPercentageOnGross) {
            if ($isIncrement) {
                $percentageAmount = $employeeCurrentGrossSalary * $how_much_amount / 100;
                $totalGross = $employeeCurrentGrossSalary + $percentageAmount;
                $totalAmount = $totalGross - $beneficialSalary;
            }
        }
        if ($isPercentageOnGross) {
            if ($isDecrement) {
                $percentageAmount = $employeeCurrentGrossSalary * $how_much_amount / 100;
                $totalGross = $employeeCurrentGrossSalary - $percentageAmount;
                $totalAmount = $totalGross - $beneficialSalary;
            }
        }

        $employee->salary = round($totalAmount);
        $employee->save();

        $item = new SalarySettlement;
        $item->employee_id = $employee->id;
        $item->amount_type = $settlement['amount_type'];
        $item->salary_type = $settlement['salary_type'];
        $item->how_much_amount = $how_much_amount;
        $item->remarks = $settlement['remarks'];
        if ($isPercentageOnGross) {
            $item->previous = $employeeCurrentGrossSalary;
        } else {
            $item->previous = $employeeCurrentSalary;
        }
        $item->after_updated = round($totalAmount);
        $item->save();

        return '';
    }

    public function departmentWiseStore(array $settlement)
    {
        abort_if(! auth()->user()->can('hrm_salary_settlement_create'), 403, 'Access Forbidden');
        $employeeIds = $settlement['employee_ids'];
        $employees = Employee::whereIn('id', $employeeIds)->get();

        $how_much_amount = $settlement['how_much_amount'];
        $isIncrement = $settlement['salary_type'] == 1; // Increment
        $isDecrement = $settlement['salary_type'] == 2; // Decrement
        $isFixed = $settlement['amount_type'] == 1;  // Percentage otherwise
        $isPercentageOnBasic = $settlement['amount_type'] == 2;  // Percentage otherwise
        $isPercentageOnGross = $settlement['amount_type'] == 3;  // Percentage otherwise

        foreach ($employees as $employee) {
            $employeeCurrentSalary = $employee->salary;
            $employeeCurrentGrossSalary = $employee->grossSalary;
            $beneficialSalary = $employee->beneficialSalary;
            if ($isFixed && $isIncrement) {
                $totalAmount = $how_much_amount + $employeeCurrentSalary;
            }
            if ($isFixed && $isDecrement) {
                $totalAmount = $employeeCurrentSalary - $how_much_amount;
            }

            if ($isPercentageOnBasic) {
                if ($isDecrement) {
                    $percentageAmount = $employeeCurrentSalary * $how_much_amount / 100;
                    $totalAmount = $employeeCurrentSalary - $percentageAmount;
                }
            }
            if ($isPercentageOnBasic) {
                if ($isIncrement) {
                    $percentageAmount = $employeeCurrentSalary * $how_much_amount / 100;
                    $totalAmount = $percentageAmount + $employeeCurrentSalary;
                }
            }
            if ($isPercentageOnGross) {
                if ($isIncrement) {
                    $percentageAmount = $employeeCurrentGrossSalary * $how_much_amount / 100;
                    $totalGross = $employeeCurrentGrossSalary + $percentageAmount;
                    $totalAmount = $totalGross - $beneficialSalary;
                }
            }
            if ($isPercentageOnGross) {
                if ($isDecrement) {
                    $percentageAmount = $employeeCurrentGrossSalary * $how_much_amount / 100;
                    $totalGross = $employeeCurrentGrossSalary - $percentageAmount;
                    $totalAmount = $totalGross - $beneficialSalary;
                }
            }
            $employee->salary = round($totalAmount);
            $employee->save();

            $item = new SalarySettlement;
            $item->employee_id = $employee->id;
            $item->amount_type = $settlement['amount_type'];
            $item->salary_type = $settlement['salary_type'];
            $item->how_much_amount = $how_much_amount;
            $item->remarks = $settlement['remarks'];
            $item->previous = $employeeCurrentSalary;
            $item->after_updated = round($totalAmount);
            $item->save();
        }

        return '';
    }

    public function lastSettlementDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_salary_settlement_delete'), 403, 'Access Forbidden');
        $employee = Employee::where('id', $id)->first();
        $last_settlement = SalarySettlement::where('employee_id', $id)->orderBy('created_at', 'DESC')->first();
        $last_increment_previous = $last_settlement->previous ?? $employee->salary;
        $employee->update([
            'salary' => $last_increment_previous,
        ]);
        $last_settlement->delete();

        return $last_settlement;
    }
}
