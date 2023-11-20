<?php

namespace Modules\HRM\Http\Requests\LeaveApplication;

use Illuminate\Foundation\Http\FormRequest;
use Modules\HRM\Interface\LeaveTypeServiceInterface;

class UpdateLeaveApplicationRequest extends FormRequest
{
    private $leaveTypeService;

    public function __construct(LeaveTypeServiceInterface $leaveTypeService)
    {
        $this->leaveTypeService = $leaveTypeService;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $maxDate = $this->leaveTypeService->find($this->all()['leave_type_id'])->days;
        $leaveApplicationRules = [
            'employee_id' => 'required',
            'leave_type_id' => 'required',
            'from_date' => ['required', function ($attribute, $value, $fail) {
                $month1 = date('F', strtotime($value));
                $month2 = date('F', strtotime($this->to_date));
                if ($month1 != $month2) {
                    $fail('The :attribute is not a valid month. Only same month entry allowed.');
                }

                $year1 = date('Y', strtotime($value));
                $year2 = date('Y', strtotime($this->to_date));
                if ($year1 != $year2) {
                    $fail('The :attribute is not a valid year. Only same year entry allowed.');
                }
            }],
            'to_date' => 'required',
            'approve_day' => "required|integer|min:0|max:$maxDate",
            'attachment' => 'nullable|mimes:jpeg,png,jpg,gif,pdf',
            'is_paid' => 'required|boolean',
            'status' => 'required|boolean',
            'reason' => 'nullable|string',
            'old_photo' => 'nullable',
        ];

        return $leaveApplicationRules;
    }

    public function messages(): array
    {
        $maxDate = $this->leaveTypeService->find($this->all()['leave_type_id'])->days;
        $date = request()->all()['to_date'];
        $formattedDate = date('d F Y', strtotime($date));

        return [
            'approve_day.min' => "Your entered start date is after $formattedDate and that's not valid.",
            'approve_day.max' => "Your approve day is exceeded $maxDate days",
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
