<?php

namespace Modules\HRM\Http\Requests\Recruitment;

use Illuminate\Foundation\Http\FormRequest;

class CreateScheduleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'interview_id' => 'required|string|max:60',
            'interviewers' => 'required|string',
            'applicant_id' => 'required|string',
            'email_template_id' => 'required|string',
            'date_time' => 'required|string',
            'descriptions' => 'nullable|string',
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
