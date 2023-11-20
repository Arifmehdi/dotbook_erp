<?php

namespace Modules\HRM\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;

class CreateVisitRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'required',
            'from_date' => 'required',
            'to_date' => 'nullable',
            'attachments' => 'nullable|mimes:jpeg,png,jpg,gif,pdf',
            'description' => 'nullable|string',
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
