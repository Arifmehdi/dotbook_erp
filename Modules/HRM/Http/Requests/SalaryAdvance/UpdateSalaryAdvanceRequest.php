<?php

namespace Modules\HRM\Http\Requests\SalaryAdvance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalaryAdvanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'required|integer',
            'permitted_by' => 'nullable|string',
            'date' => 'required|string',
            'amount' => 'required|integer',
            'month' => 'required|integer',
            'year' => 'nullable',
            'detail' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            //    'approve_day' => 'Your enter date is not valid'
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
