<?php

namespace Modules\HRM\Http\Requests\SalarySettlement;

use Illuminate\Foundation\Http\FormRequest;

class SingleSettlementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'required',
            'amount_type' => 'required',
            'salary_type' => 'required',
            'previous' => 'nullable|numeric',
            'how_much_amount' => 'required|numeric',
            'after_updated' => 'nullable|numeric',
            'remarks' => 'nullable',
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
