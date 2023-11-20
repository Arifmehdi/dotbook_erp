<?php

namespace Modules\HRM\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeList implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithMapping
{
    private $employees;

    protected $title;

    public function __construct($employees, $title)
    {
        $this->employees = $employees;
        $this->title = $title;
    }

    public function collection()
    {
        $finalCollection = $this->employees->get();

        return $finalCollection;
    }

    public function map($finalCollection): array
    {
        return [
            $finalCollection->employeeId,
            $finalCollection->employee_name,
            $finalCollection->phone,
            $finalCollection->alternative_phone,
            $finalCollection->photo,
            $finalCollection->dob,
            $finalCollection->nid,
            $finalCollection->birth_certificate,
            $finalCollection->attachments,
            $finalCollection->marital_status,
            $finalCollection->gender,
            $finalCollection->blood,
            $finalCollection->country,
            $finalCollection->father_name,
            $finalCollection->mother_name,
            $finalCollection->religion,
            $finalCollection->email,
            $finalCollection->login_access,
            $finalCollection->home_phone,
            $finalCollection->emergency_contact_person_name,
            $finalCollection->emergency_contact_person_phone,
            $finalCollection->emergency_contact_person_relation,
            $finalCollection->present_division_id,
            $finalCollection->present_district_id,
            $finalCollection->present_upazila_id,
            $finalCollection->present_union_id,
            $finalCollection->present_village,
            $finalCollection->permanent_division_id,
            $finalCollection->permanent_district_id,
            $finalCollection->permanent_upazila_id,
            $finalCollection->permanent_union_id,
            $finalCollection->permanent_village,
            $finalCollection->shift_id,
            $finalCollection->hrm_department_id,
            $finalCollection->section_id,
            $finalCollection->sub_section_id,
            $finalCollection->designation_id,
            $finalCollection->grade_id,
            $finalCollection->duty_type_id,
            $finalCollection->joining_date,
            $finalCollection->employee_type,
            $finalCollection->salary,
            $finalCollection->overtime_allowed,
            $finalCollection->starting_shift_id,
            $finalCollection->starting_salary,
            $finalCollection->employment_status,
            $finalCollection->resign_date,
            $finalCollection->left_date,
            $finalCollection->termination_date,
            $finalCollection->bank_branch_name,
            $finalCollection->bank_name,
            $finalCollection->bank_account_name,
            $finalCollection->mobile_banking_provider,
            $finalCollection->mobile_banking_account_number,
        ];
    }

    public function headings(): array
    {
        return [
            [
                'Employee List -  Dotbook ERP',
            ],
            [
                'Employee ID',
                'Employee Name',
                'Phone',
                'Alternative Phone',
                'Photo',
                'DOB(Form:Y-m-d)',
                'NID',
                'Birth Certificate(Form:Y-m-d)',
                'Attachments',
                'Marital Status',
                'Gender',
                'Blood',
                'Country',
                'Father Name',
                'Mother Name',
                'Religion',
                'Email',
                'Login Access',
                'Home Phone',
                'Emergency Contact Person',
                'Emergency Contact Person Phone',
                'Emergency Contact Person Relation',
                'Present Division',
                'Present District',
                'Present Upazila',
                'Present Union',
                'Present Village',
                'Permanent Division',
                'Permanent District',
                'Permanent Upazila',
                'Permanent Union',
                'Permanent Village',
                'Shift ID',
                'HrmDepartment ID',
                'Section ID',
                'Subsection ID',
                'Designation ID',
                'Grade ID',
                'Duty Type ID *',
                'Joining Date (Fom: Y-m-d) *',
                'Employee Type *',
                'Salary *',
                'Overtime Allowed *',
                'Starting Shift ID *',
                'Starting Salary *',
                'Employment Status *',
                'Resign Date',
                'Left Date',
                'Termination Date(Form:Y-m-d)',
                'Bank Branch Name',
                'Bank Name',
                'Bank Account Name',
                'Mobile Banking Provider',
                'Mobile Banking Account Number',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:BB1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('left');
        $sheet
            ->getStyle('A1')
            ->getFont()
            ->setBold(true)
            ->setSize(14)
            ->getColor()
            ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet
            ->getStyle('A2:BB2')
            ->getFont()
            ->setBold(true)
            ->getColor()
            ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet
            ->getStyle('A2:BB2')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('black');

        return [
            2 => ['font' => ['bold' => true]],
        ];
    }
}
