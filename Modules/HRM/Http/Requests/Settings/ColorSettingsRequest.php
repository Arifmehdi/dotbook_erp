<?php

namespace Modules\HRM\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class ColorSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'color_settings__sidebar' => 'required|string',
            'color_settings__topbar' => 'required|string',
            'color_settings__footer' => 'required|string',
            'color_settings__table' => 'required|string',
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
