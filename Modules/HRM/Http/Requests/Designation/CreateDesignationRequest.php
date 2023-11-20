<?php

namespace Modules\HRM\Http\Requests\Designation;

use Illuminate\Foundation\Http\FormRequest;

class CreateDesignationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'section_id' => 'required|numeric',
            'name' => 'required|string|max:50|unique:hrm.designations',
            'parent_designation_id' => 'nullable|numeric',
            'details' => 'nullable|string',
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
