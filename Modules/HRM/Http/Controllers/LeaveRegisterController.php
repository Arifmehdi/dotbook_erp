<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Utils\DateTimeUtils;
use Modules\HRM\Http\Requests\Leave\LeaveReportPrintRequest;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\LeaveRegisterServiceInterface;
use Modules\HRM\Interface\LeaveServiceInterface;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class LeaveRegisterController extends Controller
{
    protected $employeeService;

    protected $leaveService;

    protected $leaveRegisterService;

    public function __construct(
        EmployeeServiceInterface $employeeService,
        LeaveServiceInterface $leaveService,
        LeaveRegisterServiceInterface $leaveRegisterService
    ) {
        $this->employeeService = $employeeService;
        $this->leaveService = $leaveService;
        $this->leaveRegisterService = $leaveRegisterService;
    }

    public function leaveRegister()
    {
        abort_if(! auth()->user()->can('hrm_leave_view'), 403, 'Access Forbidden');
        $years = DateTimeUtils::years_array();
        $employees = $this->employeeService->employeeActiveListWithId();

        return view('hrm::leave-register.index', compact('years', 'employees'));
    }

    public function leaveReportPrint(LeaveReportPrintRequest $request)
    {
        $attributes = $request->validated();
        $employee_id = $attributes['employee_id'];
        $year = $attributes['year'];
        $employee = $this->employeeService->getById($employee_id);
        $leaves = $this->leaveService->getTypeWiseYearlyLeaves($employee_id, $year);
        $opening_balance = $this->leaveRegisterService->getYearlyLeaveOpening($employee, $year);
        $leaves['total_el'] = $this->leaveRegisterService->getPresentAndLateStatusCount($employee->id, $year);
        $pdf = Pdf::loadView('hrm::leave-register.print', compact('employee', 'leaves', 'opening_balance', 'year'));

        return $pdf->stream();
    }
}
