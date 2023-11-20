<?php

namespace Modules\HRM\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'nullable',
            'shift_id' => 'nullable',
            'clock_in' => 'nullable',
            'clock_out' => 'nullable',
            'at_date' => 'nullable',
            'at_date_ts' => 'nullable',
            'clock_in_ts' => 'nullable',
            'clock_out_ts' => 'nullable',
            'month' => 'nullable',
            'year' => 'nullable',
            'bm_clock_in' => 'nullable',
            'bm_clock_in_ts' => 'nullable',
            'bm_clock_out' => 'nullable',
            'bm_clock_out_ts' => 'nullable',
            'holiday_id' => 'nullable',
            'shift' => 'nullable',
            'leave_type' => 'nullable',
            'manual_entry' => 'nullable',
            'status' => 'nullable',
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
