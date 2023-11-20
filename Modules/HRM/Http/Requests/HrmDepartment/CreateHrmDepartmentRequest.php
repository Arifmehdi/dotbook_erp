<?php

namespace Modules\HRM\Http\Requests\HrmDepartment;

use Illuminate\Foundation\Http\FormRequest;

class CreateHrmDepartmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:hrm.hrm_departments',
            'updated_at' => 'required_if:created_at,2022-01-03|date',
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
