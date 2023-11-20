<?php

namespace Modules\HRM\Http\Requests\ELPayments;

use Illuminate\Foundation\Http\FormRequest;

class UpdateELPaymentsApplicationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'required|numeric',
            'year' => 'required',
            'el_days' => 'required',
            'payment_amount' => 'required|numeric',
            'payment_date' => 'required',
            'payment_type_id' => 'required|numeric',
            'remarks' => 'nullable|string',
            'status' => 'nullable|boolean',

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
