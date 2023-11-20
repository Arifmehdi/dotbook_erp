<?php

namespace Modules\HRM\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalaryExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:AG' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Joining Date',
            'Section',
            'Designation',
            'Grade',
            'Gross Salary',
            'Basic',
            'House Rent',
            'Medical',
            'Food',
            'Transport',
            'Months Day',
            'Working Day',
            'Present',
            'Absent',
            'Leave',
            'Offday',
            'Attendance Bonus',
            'Tiffin Days',
            'Tiffin Bill',
            'Night Days',
            'Night Bill',
            'OT/H',
            'OT Rate',
            'OT Amount',
            'Other Earn',
            'Gross Pay',
            'Absent Amount',
            'Advance',
            'Tax',
            'Stamp',
            'Total Deductions',
            'Net Payable Salary',
            'Mobile Bank A/C',
        ];
    }

    public function array(): array
    {
        [
            'employees' => $employees,
            'days_in_month' => $days_in_month
        ] = $this->data;
        $data_arr = [];
        foreach ($employees as $employee) {

            $data = [];
            $data['employee_id'] = $employee->employee_id ?? '';
            $data['name'] = $employee->employee_name ?? '';
            $data['joining_date'] = $employee->joining_date ?? '';
            $data['section_name'] = $employee->section_name ?? '';
            $data['designation_name'] = $employee->designation_name ?? '';
            $data['grade_name'] = $employee->grade_name ?? '';
            $data['salary'] = $employee->salary ?? '0';
            $data['basic'] = $employee->basic ?? '0';
            $data['house_rent'] = $employee->house_rent ?? '0';
            $data['medical'] = $employee->medical ?? '0';
            $data['food'] = $employee->food ?? '0';
            $data['transport'] = $employee->transport ?? '0';
            $data['days_in_month'] = $days_in_month ?? '0';
            $data['working_days'] = $employee->working_days ?? '0';
            $data['present'] = $employee->present ?? '0';
            $data['absent'] = $employee->absent ?? '0';
            $data['leaves'] = $employee->leaves ?? '0';
            $data['off_days'] = $employee->off_days ?? '0';
            $data['attendance_bonus'] = $employee->attendance_bonus ?? '0';
            $data['tiffin_days'] = $employee->tiffin_days ?? '0';
            $data['tiffin_bill'] = $employee->tiffin_bill ?? '0';
            $data['night_bill_days'] = $employee->night_bill_days ?? '0';
            $data['night_bill'] = $employee->night_bill ?? '0';
            $data['over_time'] = $employee->over_time ?? '0';
            $data['over_time_rate'] = $employee->over_time_rate ?? '0';
            $data['over_time_amount'] = $employee->over_time_amount ?? '0';
            $data['other_earning'] = $employee->other_earning ?? '0';
            $data['gross_pay'] = $employee->gross_pay ?? '0';
            $data['absent_amount'] = $employee->absent_amount ?? '0';
            $data['advance'] = $employee->advance ?? '0';
            $data['tax'] = $employee->tax ?? '0';
            $data['stamp'] = $employee->stamp ?? '0';
            $data['total_deductions'] = $employee->total_deductions ?? '0';
            $data['payable_salary'] = $employee->payable_salary ?? '0';
            $data['rocket'] = $employee->mobile_banking_account_number ?? '';
            array_push($data_arr, $data) ?? '0';
        }

        return $data_arr;
    }
}
