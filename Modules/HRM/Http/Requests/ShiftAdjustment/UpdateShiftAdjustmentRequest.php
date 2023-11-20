<?php

namespace Modules\HRM\Http\Requests\ShiftAdjustment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftAdjustmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shift_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'late_count' => 'required',
            'applied_date_from' => 'required',
            'applied_date_to' => 'required',
            'with_break' => 'nullable',
            'break_start' => 'nullable',
            'break_end' => 'nullable',
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
