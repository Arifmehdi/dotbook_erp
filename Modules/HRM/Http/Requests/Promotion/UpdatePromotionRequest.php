<?php

namespace Modules\HRM\Http\Requests\Promotion;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromotionRequest extends FormRequest
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
            'new_hrm_department_id' => 'required|integer',
            'new_subsection_id' => 'required|integer',
            'new_designation_id' => 'required|integer',
            'new_section_id' => 'required|integer',
            'promoted_date' => 'nullable',
            'user_id' => 'required',
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
