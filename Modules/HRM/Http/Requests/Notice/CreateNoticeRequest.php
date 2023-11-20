<?php

namespace Modules\HRM\Http\Requests\Notice;

use Illuminate\Foundation\Http\FormRequest;

class CreateNoticeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'nullable',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            'notice_by' => 'nullable',
            'is_active' => 'nullable|boolean',
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
