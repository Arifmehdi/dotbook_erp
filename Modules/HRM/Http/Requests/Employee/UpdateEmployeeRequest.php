<?php

namespace Modules\HRM\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Core\Enums\BloodGroups;
use Modules\Core\Enums\Countries;
use Modules\Core\Enums\Gender;
use Modules\Core\Enums\MaritalStatus;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $hrmRules = [];
        $requestKeys = request()->all();

        // For all request with prefix of 'hrm_' will be required
        if (request()->login_access == 1) {
            foreach ($requestKeys as $key => $value) {
                if (str_starts_with(trim($key), 'hrm')) {
                    $hrmRules[$key] = 'min:3';
                }
            }
        }

        if (request()->login_access == 1) {
            $employeeRules = [
                'login_access' => 'nullable|boolean',
                'username' => 'required_if:login_access,1|string|unique:users,username',
                'password' => 'required_if:login_access,1|string|min:6|confirmed',
            ];
        } else {
            $employeeRules = [
                'login_access' => 'nullable|boolean',
                'username' => 'nullable',
                'password' => 'nullable',
            ];
        }

        if (request()->p_same == 1) {
            $addressRules = [
                'present_division_id' => 'nullable',
                'present_district_id' => 'nullable',
                'present_upazila_id' => 'nullable',
                'present_union_id' => 'nullable',
                'present_village' => 'nullable|max:255',
            ];
        }

        if (request()->p_same != 1) {
            $addressRules = [
                'present_division_id' => 'required|numeric',
                'present_district_id' => 'required|numeric',
                'present_upazila_id' => 'required|numeric',
                'present_union_id' => 'nullable',
                'present_village' => 'required|string|max:255',
            ];
        }

        $create_employee_rules = [
            'job_candidate_id' => 'nullable',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'alternative_phone' => 'nullable|string|max:255',
            'photo' => 'file|nullable',
            'dob' => 'required|date|max:255',
            'nid' => 'required|string|min:5',
            'birth_certificate' => 'nullable|string|max:255',
            'marital_status' => ['required', new Enum(MaritalStatus::class)],
            'gender' => ['required', new Enum(Gender::class)],
            'blood' => ['required', new Enum(BloodGroups::class)],
            'country' => ['required', new Enum(Countries::class)],
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'emergency_contact_person_name' => 'nullable|string|max:255',
            'emergency_contact_person_phone' => 'nullable|string|max:255',
            'emergency_contact_person_relation' => 'nullable|string|max:255',

            'permanent_division_id' => 'required|numeric',
            'permanent_district_id' => 'required|numeric',
            'permanent_upazila_id' => 'required|numeric',
            'permanent_union_id' => 'nullable',
            'permanent_village' => 'required|string|max:255',

            'employee_id' => 'required|string|unique:hrm.employees,employee_id,'.$this->id,
            'shift_id' => 'required|numeric',
            'hrm_department_id' => 'required|numeric',
            'section_id' => 'required|numeric',
            'sub_section_id' => 'required|numeric',
            'designation_id' => 'required|numeric',
            'grade_id' => 'required|numeric',
            'duty_type_id' => 'required|numeric',
            'joining_date' => 'required|date',
            'employee_type' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric',
            'shift_id' => 'required|numeric',
            'starting_salary' => 'required|numeric',
            'employment_status' => 'nullable|numeric',
            'resign_date' => 'nullable|date',
            'left_date' => 'nullable|date',
            'termination_date' => 'nullable|date',

            'bank_name' => 'nullable|string|max:255',
            'bank_branch_name' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|numeric',
            'mobile_banking_provider' => 'nullable|string|max:255',
            'mobile_banking_account_number' => 'nullable|numeric',
        ];

        $rules = array_merge($employeeRules, $addressRules, $create_employee_rules);

        return $rules;
    }

    public function messages()
    {
        return [
            'username.required_if' => 'Username is required when login access is enabled.',
            'password.required_if' => 'Password is required when login access is enabled.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
