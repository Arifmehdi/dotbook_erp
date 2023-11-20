<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\SalarySettlement;
use Modules\HRM\Http\Requests\SalarySettlement\MultipleSettlementRequest;
use Modules\HRM\Http\Requests\SalarySettlement\SingleSettlementRequest;
use Modules\HRM\Interface\SalarySettlementServiceInterface;
use Modules\HRM\Service\ArrivalService;
use PDF;
use Yajra\DataTables\Facades\DataTables;

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
        $count = $this->arrivalService->getRowCount();
        if ($request->ajax()) {
            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="employee_id[]" value="'.$row->id.'" class="mt-2 check1">
                    </div>';

                    return $html;
                })
                ->editColumn('section', function ($row) {
                    return $row->section->name ?? 'Section is not Specified';
                })
                ->editColumn('designation_id', function ($row) {
                    return $row->designation->name ?? 'Designation is not Specified';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('hrm_salary_settlement_view')) {
                        $html .= '<a class="dropdown-item" style="border-bottom: 1px solid #d7d7d7;" href="'.route('hrm.salary-settlements.show', $row->id).'" id="view"><i class="fa-duotone fa-eye"></i> View List</a>';
                    }
                    if (auth()->user()->can('hrm_salary_settlement_create')) {
                        $html .= '<a class="dropdown-item increment" href="'.route('hrm.single.salary.settlement', $row->id).'" id="increment"><i class="fa-duotone fa-jet-fighter-up"></i> Add Settlement</a>';
                    }
                    if (auth()->user()->can('hrm_salary_settlement_view')) {
                        $html .= '<a class="dropdown-item statement" target="__blank" href="'.route('hrm.salary.statement', $row->id).'"><i class="fa-regular fa-coins"></i> Statement</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check', 'employee', 'section'])
                ->with([
                    'allRow' => $count,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::salary_statements.index');
    }

    public function bulkAction(Request $request)
    {
        abort_if(! auth()->user()->can('hrm_salary_settlement_create'), 403, 'Access Forbidden');
        $employee_id = $request->employee_id;
        $id = $request->employee_id[0];
        $emp = Employee::find($id);
        $name = $emp->hrmDepartment->name;

        if (isset($request->employee_id)) {
            if ($request->action_type == 'increment_or_decrement') {
                return view('hrm::salary_statements.ajax_views.department', compact('name', 'employee_id'));
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function singleSalarySettlement($id)
    {
        abort_if(! auth()->user()->can('hrm_salary_settlement_create'), 403, 'Access Forbidden');
        $employee = Employee::find($id);
        $total = $employee->grossSalary;
        $beneficialSalary = $employee->beneficialSalary;

        return view('hrm::salary_statements.ajax_views.create', compact('employee', 'total', 'beneficialSalary'));
    }

    public function store(SingleSettlementRequest $request)
    {
        abort_if(! auth()->user()->can('hrm_salary_settlement_create'), 403, 'Access Forbidden');
        $this->salarySettlementService->store($request->validated());

        return response()->json('Salary Settlement successfully');
    }

    public function departmentWiseStore(MultipleSettlementRequest $request)
    {
        abort_if(! auth()->user()->can('hrm_salary_settlement_index'), 403, 'Access Forbidden');
        $attributes = $request->validated();
        $attributes['employee_ids'] = json_decode($attributes['employee_ids']);
        $this->salarySettlementService->departmentWiseStore($attributes);

        return response()->json('Salary Settlement successfully');
    }

    public function show($id)
    {
        abort_if(! auth()->user()->can('hrm_salary_settlement_view'), 403, 'Access Forbidden');
        $settlements = SalarySettlement::where('employee_id', $id)->get();
        $employee = Employee::where('id', $id)->first();

        return view('hrm::salary_statements.ajax_views.view', compact('settlements', 'employee'));
    }

    public function salaryStatement($id)
    {
        abort_if(! auth()->user()->can('hrm_salary_settlement'), 403, 'Access Forbidden');
        $generalSettings = GeneralSetting::first();
        $employee = Employee::find($id);
        $statements = SalarySettlement::where('employee_id', $employee->id)->get();
        $pdf = PDF::loadView('hrm::salary_statements.ajax_views.statement', compact('employee', 'statements', 'generalSettings'));

        return $pdf->stream('increment-statement.pdf');
    }

    public function deleteLastSettlement($id)
    {
        abort_if(! auth()->user()->can('hrm_salary_settlement_delete'), 403, 'Access Forbidden');
        $this->salarySettlementService->lastSettlementDelete($id);

        return response()->json('Settlement deleted successfully');
    }
}
