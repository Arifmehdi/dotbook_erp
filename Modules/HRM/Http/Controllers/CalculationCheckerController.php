<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Interface\CalculationCheckerServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\JobCardServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;

class CalculationCheckerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function __construct(private EmployeeServiceInterface $employeeService, private SectionServiceInterface $sectionService, private CalculationCheckerServiceInterface $calculationCheckerService, private JobCardServiceInterface $jobCardService)
    {
    }

    public function index(Request $request)
    {
        $employees = $this->employeeService->employeeActiveListWithId();
        $sections = $this->sectionService->all();

        return view('hrm::payroll.calculation_checker.index', compact('employees', 'sections'));
    }

    public function checkJobCardAndSalary(Request $request)
    {
        $jobCardVsSummary = $this->calculationCheckerService->checkJobCardAndSalary($request);
    }

    public function checkSummaryAndSalary(Request $request)
    {
        $summaryVsSalary = $this->calculationCheckerService->checkSummaryAndSalary($request);
    }

    public function checkAllCalculation(Request $request)
    {
        $allCalculation = $this->calculationCheckerService->checkAllCalculation($request);
    }
}
