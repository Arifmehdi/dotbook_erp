<?php

namespace Modules\HRM\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Enums\EmployeeExcelColumns;

class EmployeeImport implements ToCollection
{
    public $error;

    public function __construct()
    {
        $this->error;
    }

    public function collection(Collection $employees)
    {
        $index = 0;
        foreach ($employees as $employee) {
            if ($index > 1) {
                Employee::create([
                    'employee_id' => $employee[EmployeeExcelColumns::EMP_ID->value],
                    'name' => $employee[EmployeeExcelColumns::NAME->value],
                    'phone' => $employee[EmployeeExcelColumns::PHONE->value],
                    'alternative_phone' => $employee[EmployeeExcelColumns::ALTERNATIVE_PHONE->value],
                    'dob' => date('Y-m-d', strtotime($employee[EmployeeExcelColumns::DOB->value])),
                    'photo' => $employee[EmployeeExcelColumns::PHOTO->value],
                    'nid' => $employee[EmployeeExcelColumns::NID->value],
                    'birth_certificate' => $employee[EmployeeExcelColumns::BIRTH_CERTIFICATE->value],
                    'attachments' => $employee[EmployeeExcelColumns::ATTACHMENTS->value],
                    'marital_status' => $employee[EmployeeExcelColumns::MARITAL_STATUS->value],
                    'gender' => $employee[EmployeeExcelColumns::GENDER->value],
                    'blood' => $employee[EmployeeExcelColumns::BLOOD->value],
                    'country' => $employee[EmployeeExcelColumns::COUNTRY->value] ? $employee[EmployeeExcelColumns::COUNTRY->value] : 'Bangladesh',
                    'father_name' => $employee[EmployeeExcelColumns::FATHER_NAME->value],
                    'mother_name' => $employee[EmployeeExcelColumns::MOTHER_NAME->value],
                    'religion' => $employee[EmployeeExcelColumns::RELIGION->value],
                    'email' => $employee[EmployeeExcelColumns::EMAIL->value],
                    'login_access' => $employee[EmployeeExcelColumns::LOGIN_ACCESS->value],
                    'home_phone' => $employee[EmployeeExcelColumns::HOME_PHONE->value],
                    'emergency_contact_person_name' => $employee[EmployeeExcelColumns::EMERGENCY_CONTACT_PERSON->value],
                    'emergency_contact_person_phone' => $employee[EmployeeExcelColumns::EMERGENCY_CONTACT_PERSON_PHONE->value],
                    'emergency_contact_person_relation' => $employee[EmployeeExcelColumns::EMERGENCY_CONTACT_PERSON_RELATION->value],
                    'present_division_id' => $employee[EmployeeExcelColumns::PRESENT_DIVISION->value],
                    'present_district_id' => $employee[EmployeeExcelColumns::PRESENT_DISTRICT->value],
                    'present_upazila_id' => $employee[EmployeeExcelColumns::PRESENT_UPAZILA->value],
                    'present_union_id' => $employee[EmployeeExcelColumns::PRESENT_UNION->value],
                    'present_village' => $employee[EmployeeExcelColumns::PRESENT_VILLAGE->value],
                    'permanent_division_id' => $employee[EmployeeExcelColumns::PERMANENT_DIVISION->value],
                    'permanent_district_id' => $employee[EmployeeExcelColumns::PERMANENT_DISTRICT->value],
                    'permanent_upazila_id' => $employee[EmployeeExcelColumns::PERMANENT_UPAZILA->value],
                    'permanent_union_id' => $employee[EmployeeExcelColumns::PERMANENT_UNION->value],
                    'permanent_village' => $employee[EmployeeExcelColumns::PERMANENT_VILLAGE->value],
                    'shift_id' => $employee[EmployeeExcelColumns::SHIFT_ID->value],
                    'hrm_department_id' => $employee[EmployeeExcelColumns::DEPARTMENT_ID->value],
                    'section_id' => $employee[EmployeeExcelColumns::SECTION_ID->value],
                    'sub_section_id' => $employee[EmployeeExcelColumns::SUBSECTION_ID->value],
                    'designation_id' => $employee[EmployeeExcelColumns::DESIGNATION_ID->value],
                    'grade_id' => $employee[EmployeeExcelColumns::GRADE_ID->value],
                    'duty_type_id' => $employee[EmployeeExcelColumns::DUTY_TYPE_ID->value],

                    'joining_date' => date('Y-m-d', strtotime($employee[EmployeeExcelColumns::JOINING_DATE->value])),
                    'employee_type' => $employee[EmployeeExcelColumns::EMPLOYEE_TYPE->value],
                    'salary' => $employee[EmployeeExcelColumns::SALARY->value],
                    'overtime_allowed' => $employee[EmployeeExcelColumns::OVERTIME_ALLOWED->value],
                    'starting_shift_id' => $employee[EmployeeExcelColumns::STARTING_SHIFT_ID->value],
                    'starting_salary' => $employee[EmployeeExcelColumns::STARTING_SALARY->value],
                    'employment_status' => $employee[EmployeeExcelColumns::EMPLOYMENT_STATUS->value],
                    'resign_date' => $employee[EmployeeExcelColumns::RESIGN_DATE->value],
                    'left_date' => $employee[EmployeeExcelColumns::LEFT_DATE->value],
                    'termination_date' => date('Y-m-d', strtotime($employee[EmployeeExcelColumns::TERMINATION_DATE->value])),
                    'bank_branch_name' => $employee[EmployeeExcelColumns::BANK_BRANCH_NAME->value],
                    'bank_name' => $employee[EmployeeExcelColumns::BANK_NAME->value],
                    'bank_account_name' => $employee[EmployeeExcelColumns::BANK_ACCOUNT_NAME->value],
                    'mobile_banking_provider' => $employee[EmployeeExcelColumns::MOBILE_BANKING_PROVIDER->value],
                    'mobile_banking_account_number' => $employee[EmployeeExcelColumns::MOBILE_BANKING_ACCOUNT_NUMBER->value],
                ]);
            }
            $index++;
        }
    }
}
