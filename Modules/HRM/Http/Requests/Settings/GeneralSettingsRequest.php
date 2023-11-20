<?php

namespace Modules\HRM\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class GeneralSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'general_settings__company_name' => 'required|string',
            'general_settings__address' => 'required|string',
            'general_settings__phone' => 'required|string',
            'general_settings__email' => 'required|string',
            'general_settings__company_slogan' => 'required|string',
            'general_settings__website' => 'required|string',
            'general_settings__custom_address_1_name' => 'required|string',
            'general_settings__custom_address_1_value' => 'required|string',
            'general_settings__custom_address_2_name' => 'required|string',
            'general_settings__custom_address_2_value' => 'required|string',
            'general_settings__custom_address_3_name' => 'required|string',
            'general_settings__custom_address_3_value' => 'required|string',
            'general_settings__favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'general_settings__app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'old_logo' => 'nullable',
            'old_favicon' => 'nullable',
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
