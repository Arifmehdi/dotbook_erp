<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\JobCardServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use Modules\HRM\Interface\ShiftServiceInterface;

class JobCardController extends Controller
{
    public function __construct(private EmployeeServiceInterface $employeeService, private JobCardServiceInterface $jobCardService, private SectionServiceInterface $sectionService, private ShiftServiceInterface $shiftService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function jobCard()
    {
        abort_if(!auth()->user()->can('hrm_attendance_job_card'), 403, 'Access forbidden');
        $departments = $this->sectionService->sectionWithHrmDepartmentAndSelection();
        $employees = $this->employeeService->employeeActiveListWithId();
        $shifts = $this->shiftService->shiftOptimized();

        return view('hrm::job_card.index', compact('employees', 'departments', 'shifts'));
    }

    public function JobCardPrint(Request $request)
    {
        abort_if(!auth()->user()->can('hrm_attendance_job_card_print'), 403, 'Access forbidden');
        [
            'month' => $month,
            'year' => $year,
            'employee' => $employee,
            'results' => $results,
            'total_present' => $total_present,
            'total_leave' => $total_leave,
            'total_absent' => $total_absent,
            'total_late' => $total_late,
            'total_overtime' => $total_overtime,
            'total_weekend' => $total_weekend,
            'employee_type' => $employee_type,
            'employee_type_date' => $employee_type_date,
        ] = $this->jobCardService->jobCardPrint($request);

        return view('hrm::job_card.job_card_print', compact(
            'month',
            'year',
            'employee',
            'results',
            'total_present',
            'total_leave',
            'total_absent',
            'total_late',
            'total_overtime',
            'total_weekend',
            'employee_type',
            'employee_type_date'
        ));
    }
}
