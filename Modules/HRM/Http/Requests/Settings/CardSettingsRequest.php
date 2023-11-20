<?php

namespace Modules\HRM\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class CardSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // "id_card_settings__company_name" => "required|string",
            // "id_card_settings__slogan" => "required|string",
            'id_card_settings__footer_left_text' => 'required|string',
            'id_card_settings__footer_right_text' => 'required|string',
            'id_card_settings__footer_right_signature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'id_card_settings__back_line_one' => 'required|string',
            'id_card_settings__back_company_short_text' => 'required|string',
            'id_card_settings__block1_header' => 'required|string',

            'id_card_settings__block1_description' => 'required|string',
            'id_card_settings__block2_header' => 'required|string',
            'id_card_settings__block2_description' => 'required|string',

            // "id_card_settings__block3_header" => "nullable|string",
            // "id_card_settings__block3_description" => "nullable|string",
            // "id_card_settings__logo" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            // "old_logo" => 'nullable',
            'old_signature' => 'nullable',

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
