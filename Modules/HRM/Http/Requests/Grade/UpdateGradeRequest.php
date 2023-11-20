<?php

namespace Modules\HRM\Http\Requests\Grade;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:100|unique:hrm.grades,name,'.request()->id,
            'basic' => 'required|numeric',
            'house_rent' => 'required|numeric',
            'medical' => 'required|numeric',
            'food' => 'required|numeric',
            'transport' => 'required|numeric',
            'other' => 'required|numeric',
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
