<?php

namespace Modules\CRM\Http\Requests\Source;

use Illuminate\Foundation\Http\FormRequest;

class SourceUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
            ],
            'description' => ['string'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Source name is required',
            'description.string' => 'Description must be string',
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
