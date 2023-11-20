<?php

namespace Modules\Core\Http\Requests\BdDivision;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBdDivisionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:bd_divisions,name,'.request()->id,
            'bn_name' => 'required|string|max:255',
            'url' => 'nullable|string',
            // 'status' => 'required|string|max:255',
            // 'country_id' => 'required|numeric',
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
