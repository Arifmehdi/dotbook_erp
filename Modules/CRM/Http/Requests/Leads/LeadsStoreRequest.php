<?php

namespace Modules\CRM\Http\Requests\Leads;

use Illuminate\Foundation\Http\FormRequest;

class LeadsStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'is_lead' => 'required',
            'contact_id' => 'string|nullable|sometimes',
            'name' => 'required',
            'date_of_birth' => 'date|nullable|sometimes',
            'business_name' => 'string|nullable|sometimes',
            'phone_number_prefix' => 'integer|nullable|sometimes',
            'phone' => 'required|unique:customers,phone',
            'alternative_phone' => 'string|nullable|sometimes',
            'landline' => 'string|nullable|sometimes',
            'email' => 'email|nullable|sometimes',
            'source_id' => 'sometimes|integer',
            'life_stage_id' => 'sometimes|integer',
            'assigned_to_ids' => 'sometimes|array',
            'tax_number' => 'string|nullable|sometimes',
            'city' => 'string|nullable|sometimes',
            'state' => 'string|nullable|sometimes',
            'country' => 'string|nullable|sometimes',
            'zip_code' => 'string|nullable|sometimes',
            'address' => 'string|nullable|sometimes',
            'photos' => 'sometimes|image|mimes:jpg,jpeg,gif,png',
            'shipping_address' => 'string|nullable|sometimes',
            'post_code' => 'string|nullable|sometimes',
            'bank_name' => 'string|nullable|sometimes',
            'bank_account_number' => 'string|nullable|sometimes',
            'bank_branch' => 'string|nullable|sometimes',
        ];
    }
}
