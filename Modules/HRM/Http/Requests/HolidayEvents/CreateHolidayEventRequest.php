<?php

namespace Modules\HRM\Http\Requests\HolidayEvents;

use Illuminate\Foundation\Http\FormRequest;

class CreateHolidayEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'title' => 'required|string',
            'start' => 'required|string',
            'end' => 'required|string',
            'color' => 'required|string',
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
