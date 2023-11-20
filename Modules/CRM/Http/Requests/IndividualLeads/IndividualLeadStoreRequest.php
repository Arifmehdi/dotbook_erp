<?php

namespace Modules\CRM\Http\Requests\IndividualLeads;

use Illuminate\Foundation\Http\FormRequest;

class IndividualLeadStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'address' => 'required',
            'companies' => 'string|nullable|sometimes',
            'description' => 'string|nullable|sometimes',
            'phone_numbers' => 'string|nullable|sometimes',
            'email_addresses' => 'email|nullable|sometimes',
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
