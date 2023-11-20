<?php

namespace Modules\HRM\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class PayrollSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payroll_settings__prepared_by_text' => 'required|string',
            'payroll_settings__checked_by_text' => 'required|string',
            'payroll_settings__approved_by_text' => 'required|string',

            'payroll_settings__prepared_by_person' => 'nullable|string',
            'payroll_settings__checked_by_person' => 'nullable|string',
            'payroll_settings__approved_by_person' => 'nullable|string',

            'payroll_settings__prepared_by_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payroll_settings__checked_by_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payroll_settings__approved_by_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'old_prepared_by_signature' => 'nullable',
            'old_checked_by_signature' => 'nullable',
            'old_approved_by_signature' => 'nullable',

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
