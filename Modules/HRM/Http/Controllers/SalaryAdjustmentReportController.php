<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Interface\ArrivalServiceInterface;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\SalaryAdjustmentServiceInterface;
use Yajra\DataTables\DataTables;

class SalaryAdjustmentReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    private $salaryAdjustmentService;

    private $arrivalService;

    private $commonService;

    protected $employeeService;

    public function __construct(ArrivalServiceInterface $arrivalService, CommonServiceInterface $commonService, SalaryAdjustmentServiceInterface $salaryAdjustmentService, EmployeeServiceInterface $employeeService)
    {
        $this->arrivalService = $arrivalService;
        $this->commonService = $commonService;
        $this->salaryAdjustmentService = $salaryAdjustmentService;
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {
        $employees = $this->employeeService->employeeActiveListWithId();
        if ($request) {
            $salaryAdjustment = $this->salaryAdjustmentService->employeeFilter($request);
        }
        $rowCount = $this->salaryAdjustmentService->getRowCount();

        if ($request->ajax()) {

            return DataTables::of($salaryAdjustment)
                ->addIndexColumn()

                ->editColumn('employeeId', function ($row) {
                    return $row->employee->employee_id ?? ' ';
                })
                ->editColumn('name', function ($row) {
                    return $row->employee->name ?? ' ';
                })
                ->addColumn('salary', function ($row) {
                    $row->salary = $row->employee->salary;

                    return $row->salary ?? 0;
                })
                ->editColumn('amount', function ($row) {
                    $row->amount = $row->amount;

                    return $row->amount ?? 0;
                })

                ->editColumn('month', function ($row) {
                    return $row->MonthName ?? ' ';
                })
                ->editColumn('type', function ($row) {
                    return ($row->type == 1) ? '✅ Addition' : '🛑 Deduction' ?? ' ';
                })
                ->addColumn('totalSalary', function ($row) {
                    if ($row->type == 1) {
                        $totalSalary = ($row->salary + $row->amount);
                    } else {
                        $totalSalary = ($row->salary - $row->amount);
                    }

                    return $totalSalary ?? 0;
                })
                ->editColumn('photo', function ($row) {
                    return $this->commonService->showAvatarImage('uploads/employees/', $row->photo);
                })

                ->rawColumns(['check', 'employee', 'photo', 'type'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::reports.salary-adjustment.index', compact('employees'));
    }
}
