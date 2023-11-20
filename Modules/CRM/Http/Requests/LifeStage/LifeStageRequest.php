<?php

namespace Modules\CRM\Http\Requests\LifeStage;

use Illuminate\Foundation\Http\FormRequest;

class LifeStageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Life Stage name is required',
        ];
    }
}
