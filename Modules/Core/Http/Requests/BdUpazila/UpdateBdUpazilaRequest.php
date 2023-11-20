<?php

namespace Modules\Core\Http\Requests\BdUpazila;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBdUpazilaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:bd_upazilas,name,'.request()->id,
            'bn_name' => 'required|string|max:255',
            'district_id' => 'required|numeric',
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
