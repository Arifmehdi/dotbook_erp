<?php

namespace Modules\CRM\Http\Requests\Followups;

use Illuminate\Foundation\Http\FormRequest;

class FollowupStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        // dd($this);
        return [
            'title' => 'required',
            'status' => 'required',
            'date' => 'required',
            'followup_category' => 'required',
            'business_id' => 'nullable',
            'individual_id' => 'nullable',
            'leads_individual_or_business' => 'nullable',
            'customers_or_leads' => 'nullable',
            'followup_type' => 'nullable',
            'description' => 'nullable',
            'file' => 'nullable',
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
