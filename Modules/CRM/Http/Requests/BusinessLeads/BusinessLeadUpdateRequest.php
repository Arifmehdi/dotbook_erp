<?php

namespace Modules\CRM\Http\Requests\BusinessLeads;

use Illuminate\Foundation\Http\FormRequest;

class BusinessLeadUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'location' => 'required|string|max:50',
            'description' => 'string|nullable|sometimes',
            'phone_numbers' => 'string|nullable|sometimes',
            'email_addresses' => 'email|nullable|sometimes',
            'total_employees' => 'integer|nullable|sometimes',
            'additional_information' => 'string|nullable|sometimes',
            'files.*' => 'mimes:png,jpg,jpeg,webp,avif,txt|nullable|sometimes',
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
