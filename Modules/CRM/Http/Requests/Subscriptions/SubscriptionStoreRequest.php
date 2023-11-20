<?php

namespace Modules\CRM\Http\Requests\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'required',
            'project_id' => 'required',
            'billing_plan' => 'nullable',
            'quantity' => 'nullable',
            'date' => 'nullable',
            'subscription_name' => 'nullable',
            'description' => 'nullable',
            'currency' => 'nullable',
            'tax' => 'nullable',
            'terms' => 'nullable',
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
