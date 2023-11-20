<?php

namespace Modules\Core\Http\Requests\BdUnion;

use Illuminate\Foundation\Http\FormRequest;

class CreateBdUnionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:bd_unions',
            'bn_name' => 'required|string|max:255',
            'upazilla_id' => 'required|numeric',
            'url' => 'nullable|string|max:255',
            // 'status' => 'required|string|max:255',
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
