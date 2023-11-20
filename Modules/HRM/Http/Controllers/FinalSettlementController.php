<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Http\Requests\FinalSettlement\FinalSettlementRequest;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\FinalSettlementServiceInterface;
use PDF;

class FinalSettlementController extends Controller
{
    private $employeeService;

    private $finalSettlementService;

    public function __construct(EmployeeServiceInterface $employeeService, FinalSettlementServiceInterface $finalSettlementService)
    {
        $this->employeeService = $employeeService;
        $this->finalSettlementService = $finalSettlementService;
    }

    public function index()
    {
        abort_if(! auth()->user()->can('hrm_final_settlement_index'), 403, 'Access Forbidden');
        $employees = $this->employeeService->employeeActiveListWithId();

        return view('hrm::final-settlement.index', compact('employees'));
    }

    public function getPaper(FinalSettlementRequest $request)
    {
        abort_if(! auth()->user()->can('hrm_final_settlement_action'), 403, 'Access Forbidden');
        $attributes = $request->validated();
        $submission_date = $attributes['submission_date'];
        $approval_date = $attributes['approval_date'];
        $stamp = $attributes['stamp'];
        $qEmployee = Employee::find($attributes['employee_id']);
        [
            'employee' => $employee,
            'el_full_report' => $el_full_report
        ] = $this->finalSettlementService->settleEmployee($qEmployee, $submission_date, $approval_date, $stamp);
        $pdf = PDF::loadView('hrm::final-settlement.settlement-paper', compact('employee', 'el_full_report'));

        return $pdf->stream();
    }
}
