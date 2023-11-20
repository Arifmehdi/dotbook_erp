<?php

namespace Modules\HRM\Http\Requests\FinalSettlement;

use Illuminate\Foundation\Http\FormRequest;

class FinalSettlementRequest extends FormRequest
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
            'submission_date' => 'required',
            'approval_date' => 'required',
            'stamp' => 'required',
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
