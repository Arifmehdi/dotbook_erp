<?php

namespace Modules\CRM\Http\Requests\ProposalTemplate;

use Illuminate\Foundation\Http\FormRequest;

class ProposalTemplateAddRequest extends FormRequest
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
            'subject' => [
                'required',
            ],
            // 'description' => [
            //     'required',
            // ],
            'file' => [
                'max: 5000',
            ],
        ];
    }

    public function messages()
    {
        return [
            'subject.required' => 'Subject is required.',
            // 'description.required' => 'Body is required.',
            'file.max' => 'File size is too large.',
        ];
    }
}
