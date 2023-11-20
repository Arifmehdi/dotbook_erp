<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Http\Requests\FinalSettlement\FinalSettlementRequest;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\FinalSettlementServiceInterface;
use Modules\HRM\Transformers\FinalSettlementResource;

class FinalSettlementController extends Controller
{
    private $employeeService;

    private $finalSettlementService;

    public function __construct(
        EmployeeServiceInterface $employeeService,
        FinalSettlementServiceInterface $finalSettlementService
    ) {
        $this->employeeService = $employeeService;
        $this->finalSettlementService = $finalSettlementService;
    }

    public function index(FinalSettlementRequest $request)
    {
        $attributes = $request->validated();
        $submission_date = $attributes['submission_date'];
        $approval_date = $attributes['approval_date'];
        $stamp = $attributes['stamp'];
        $qEmployee = Employee::find($attributes['employee_id']);

        [
            'employee' => $employee,
            'el_full_report' => $el_full_report
        ] = $this->finalSettlementService->settleEmployee($qEmployee, $submission_date, $approval_date, $stamp);

        return new FinalSettlementResource([
            'employee' => $employee,
            'report' => $el_full_report,
        ]);
    }
}
