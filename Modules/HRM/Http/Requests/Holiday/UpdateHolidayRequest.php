<?php

namespace Modules\HRM\Http\Requests\Holiday;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHolidayRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->from <= request()->to) {
            $holidayRules = [
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:50',
                'from' => 'required',
                'to' => 'required',
                'num_of_days' => 'required|numeric',
            ];
        } else {
            $holidayRules = [
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:50',
                'from' => 'required',
                'to' => 'required',
                'num_of_days' => 'required|numeric|min:0',
            ];
        }

        return $holidayRules;
    }

    public function messages(): array
    {
        return [
            'num_of_days' => 'Your enter date is not valid',
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
