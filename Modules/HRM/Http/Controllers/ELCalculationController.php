<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Utils\DateTimeUtils;
use Modules\HRM\Interface\ELCalculationServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class ELCalculationController extends Controller
{
    private $employeeService;

    protected $elCalculationService;

    public function __construct(
        EmployeeServiceInterface $employeeService,
        ELCalculationServiceInterface $elCalculationService,
    ) {
        $this->employeeService = $employeeService;
        $this->elCalculationService = $elCalculationService;
    }

    public function index(Request $request)
    {
        $years = DateTimeUtils::years_array();
        if ($request->ajax()) {
            $year = $request->year ?? date('Y');
            $employees = $this->elCalculationService->getEL_Calculation($year);
            $rowCount = count($employees);

            return DataTables::of($employees)
                ->addIndexColumn()
                ->editColumn('joining_date', function ($employee) {
                    return date('d F, Y', strtotime($employee->joining_date));
                })
                ->editColumn('taken_el', function ($employee) {
                    if ($employee->taken_el) {
                        $enjoyed = $employee->enjoyed_el_count;
                        $paid = $employee->el_paid_days;
                        $total = $enjoyed + $paid;

                        return "$enjoyed + $paid = $total";
                    }

                    return $employee->taken_el;
                })
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::el_calculation.index', compact('years'));
    }
}
