<?php

namespace Modules\HRM\Http\Requests\LeaveType;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeaveTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:60|unique:hrm.grades',
            'for_months' => 'required|numeric|max:12',
            'days' => 'required|numeric',
            'is_active' => 'required|boolean',
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
