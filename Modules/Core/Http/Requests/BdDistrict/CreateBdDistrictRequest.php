<?php

namespace Modules\Core\Http\Requests\BdDistrict;

use Illuminate\Foundation\Http\FormRequest;

class CreateBdDistrictRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:bd_districts',
            'bn_name' => 'required|string|max:255',
            'lat' => 'nullable|string',
            'lon' => 'nullable|string',
            'url' => 'nullable|string|max:255',
            'division_id' => 'required|numeric',
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
