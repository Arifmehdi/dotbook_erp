<?php

namespace Modules\CRM\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'schedule_date' => ['required'],
            'schedule_time' => ['required'],
            'customer_id' => ['required'],
            'appointor_id' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'schedule_date.required' => 'Schedule date is required.',
            'schedule_time.required' => 'Schedule time is required.',
            'appointor_id.required' => 'Appointor is required.',
            'customer_id.required' => 'Customer is required.',
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
