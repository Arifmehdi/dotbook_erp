<?php

namespace Modules\Core\Http\Requests\BdDivision;

use Illuminate\Foundation\Http\FormRequest;

class CreateBdDivisionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:bd_divisions',
            'bn_name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
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
