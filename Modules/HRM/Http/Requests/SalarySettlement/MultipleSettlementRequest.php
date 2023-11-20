<?php

namespace Modules\HRM\Http\Requests\SalarySettlement;

use Illuminate\Foundation\Http\FormRequest;

class MultipleSettlementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_ids' => 'required',
            'amount_type' => 'required',
            'salary_type' => 'required',
            'previous' => 'nullable|numeric', // ata required celo
            'how_much_amount' => 'required|numeric',
            'after_updated' => 'nullable|numeric', // ata required celo
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
