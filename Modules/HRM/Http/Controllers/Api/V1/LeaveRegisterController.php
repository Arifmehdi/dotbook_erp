<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Modules\HRM\Http\Requests\Leave\LeaveReportPrintRequest;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\LeaveRegisterServiceInterface;
use Modules\HRM\Interface\LeaveServiceInterface;
use Modules\HRM\Transformers\LeaveRegisterReportResource;

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

    public function leaveRegisterReport(LeaveReportPrintRequest $request)
    {
        $attributes = $request->validated();
        $employee_id = $attributes['employee_id'];
        $year = $attributes['year'];
        $employee = $this->employeeService->getById($employee_id);
        $leaves = $this->leaveService->getTypeWiseYearlyLeaves($employee_id, $year);
        $opening_balance = $this->leaveRegisterService->getYearlyLeaveOpening($employee, $year);
        $leaves['total_el'] = $this->leaveRegisterService->getPresentAndLateStatusCount($employee->id, $year);

        $leaveRegisterReport = [
            'employee' => $employee,
            'opening_balance' => $opening_balance,
            'leaves' => $leaves,
        ];
        // return LeaveRegisterReportResource::collection($leaveRegisterReport);
        // $leaveReportCollection = LeaveRegisterReportResource::collection($leaveRegisterReport);
        return $leaveRegisterReport;

    }
}
