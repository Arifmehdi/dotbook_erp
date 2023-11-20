<?php

namespace Modules\CRM\Http\Requests\Leads;

use Illuminate\Foundation\Http\FormRequest;

class LeadsUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'is_lead' => 'required',
            'name' => 'required',
            'date_of_birth' => 'date|nullable|sometimes',
            'business_name' => 'string|nullable|sometimes',
            'phone_number_prefix' => 'string|nullable|sometimes',
            'phone' => 'required|unique:customers,phone,'.$this->id,
            'alternative_phone' => 'string|nullable|sometimes',
            'landline' => 'string|nullable|sometimes',
            'email' => 'sometimes|email',
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
