<?php

namespace Modules\HRM\Http\Requests\Award;

use Illuminate\Foundation\Http\FormRequest;

class CreateAwardRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'required|integer',
            'award_name' => 'required|string',
            'award_description' => 'nullable | string',
            'gift_item' => 'required | string',
            'award_by' => 'required | string',
            'date' => 'required|string',
            'month' => 'nullable| string',
            'year' => 'nullable| integer',
        ];

    }

    public function messages(): array
    {
        return [

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
