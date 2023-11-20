<?php

namespace Modules\HRM\Http\Requests\Increment;

use Illuminate\Foundation\Http\FormRequest;

class IncrementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'increment_type' => 'required',
            'previous' => 'required',
            'increment_amount' => 'required',
            'after_updated' => 'required',
            'increment_details' => 'nullable',
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
