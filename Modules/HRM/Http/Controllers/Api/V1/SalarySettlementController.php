<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\SalarySettlement;
use Modules\HRM\Http\Requests\SalarySettlement\MultipleSettlementRequest;
use Modules\HRM\Http\Requests\SalarySettlement\SingleSettlementRequest;
use Modules\HRM\Interface\SalarySettlementServiceInterface;
use Modules\HRM\Service\ArrivalService;
use Modules\HRM\Transformers\SalarySettlementResource;

class SalarySettlementController extends Controller
{
    protected $arrivalService;

    protected $salarySettlementService;

    public function __construct(ArrivalService $arrivalService, SalarySettlementServiceInterface $salarySettlementService)
    {
        $this->arrivalService = $arrivalService;
        $this->salarySettlementService = $salarySettlementService;
    }

    public function index(Request $request)
    {
        $employees = $this->arrivalService->activeEmployeeFilter($request);
        $employeeCount = $this->arrivalService->getRowCount();
        $employeesResource = SalarySettlementResource::collection($employees);

        return response()->json([
            'employee_type' => 'Active',
            'total_employee' => $employeeCount,
            'employees' => $employeesResource,
        ]);
    }

    public function singleSalarySettlement($id)
    {
        $employee = Employee::find($id);
        $total = $employee->grossSalary;
        $beneficialSalary = $employee->beneficialSalary;

        return view('hrm::salary_statements.ajax_views.create', compact('employee', 'total', 'beneficialSalary'));
    }

    public function store(SingleSettlementRequest $request)
    {
        $this->salarySettlementService->store($request->validated());

        return response()->json('Salary Settlement successfully');
    }

    public function departmentWiseStore(MultipleSettlementRequest $request)
    {
        $attributes = $request->validated();
        $this->salarySettlementService->departmentWiseStore($attributes);

        return response()->json('Salary Settlement successfully');
    }

    public function show($id)
    {
        $settlements = SalarySettlement::where('employee_id', $id)->get();
        $employee = Employee::where('id', $id)->first();

        return response()->json($settlements);
    }

    public function deleteLastSettlement($id)
    {
        $this->salarySettlementService->lastSettlementDelete($id);

        return response()->json('Settlement deleted successfully');
    }
}
