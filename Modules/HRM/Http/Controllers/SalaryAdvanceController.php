<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\SalaryAdvance\CreateSalaryAdvanceRequest;
use Modules\HRM\Http\Requests\SalaryAdvance\UpdateSalaryAdvanceRequest;
use Modules\HRM\Interface\ArrivalServiceInterface;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\SalaryAdvanceServiceInterface;
use Yajra\DataTables\DataTables;

class SalaryAdvanceController extends Controller
{
    private $salaryAdvanceService;

    private $commonService;

    private $arrivalService;

    public function __construct(SalaryAdvanceServiceInterface $salaryAdvanceService, CommonServiceInterface $commonService, ArrivalServiceInterface $arrivalService)
    {
        $this->salaryAdvanceService = $salaryAdvanceService;
        $this->commonService = $commonService;
        $this->arrivalService = $arrivalService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $salaryAdvance = $this->salaryAdvanceService->getTrashedItem();
        } else {
            $salaryAdvance = $this->salaryAdvanceService->employeeFilter($request);
        }

        $rowCount = $this->salaryAdvanceService->getRowCount();
        $trashedCount = $this->salaryAdvanceService->getTrashedCount();

        if ($request->ajax()) {

            return DataTables::of($salaryAdvance)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="salary_advance_id[]" value="'.$row->id.'" class="mt-2 check1">
                    </div>';

                    return $html;
                })
                ->editColumn('employeeId', function ($row) {
                    return $row->employee->employee_id ?? 'Employee Id is not Specified';
                })
                ->editColumn('name', function ($row) {
                    return $row->employee->name ?? 'Employee name is not Specified';
                })
                ->editColumn('permitted_by', function ($row) {
                    return $row->permitter->name ?? 'Permitted person is not Specified';
                })
                ->editColumn('month', function ($row) {
                    return $row->MonthName ?? 'Month is not Specified';
                })
                ->addColumn('action', function ($row) {
                    $action1 = '';
                    $action2 = '';
                    $type = '';
                    $icon1 = '';
                    $icon2 = '';
                    if ($row->trashed()) {
                        $action1 = 'restore';
                        $action2 = 'permanent-delete';
                        $type = 'restore';
                        $icon1 = '<i class="fa-solid fa-recycle"></i>';
                        $icon2 = '<i class="fa-solid fa-trash-check"></i>';
                    } else {
                        $action1 = 'edit';
                        $action2 = 'destroy';
                        $type = 'Edit';
                        $icon1 = '<span class="fas fa-edit"></span></a>';
                        $icon2 = '<span class="fas fa-trash "></span>';
                    }

                    $html = '<div class="dropdown table-dropdown">';

                    if (auth()->user()->can('hrm_salary_advances_update')) {
                        $html .= '<a href="'.route('hrm.salary-advances.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_salary_advances" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_salary_advances_delete')) {
                        $html .= '<a href="'.route('hrm.salary-advances.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_salary_advances" title="Delete">'.$icon2.'</a>';
                    }
                    $html .= '</div>';

                    return $html;
                })

                ->rawColumns(['action', 'check'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::salary_adjustment.advance.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create(Request $request)
    {
        $employees = $this->arrivalService->activeEmployeeFilter($request);

        return view('hrm::salary_adjustment.advance.ajax_views.add', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateSalaryAdvanceRequest $request)
    {
        $this->salaryAdvanceService->store($request->validated());

        return response()->json('Salary advance added successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit(Request $request, $id)
    {
        $salaryAdvance = $this->salaryAdvanceService->find($id);
        $employees = $this->arrivalService->activeEmployeeFilter($request);

        return view('hrm::salary_adjustment.advance.ajax_views.edit', compact('salaryAdvance', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateSalaryAdvanceRequest $request, $id)
    {
        $salaryAdvance = $this->salaryAdvanceService->Update($request->validated(), $id);

        return response()->json('Salary advance updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $salaryAdvance = $this->salaryAdvanceService->trash($id);

        return response()->json('Salary advance deleted successfully');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->salary_advance_id)) {
            if ($request->action_type == 'move_to_trash') {
                $salaryAdvance = $this->salaryAdvanceService->bulkTrash($request->salary_advance_id);

                return response()->json('Salary advance are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $salaryAdvance = $this->salaryAdvanceService->bulkRestore($request->salary_advance_id);

                return response()->json('Salary advance are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $salaryAdvance = $this->salaryAdvanceService->bulkPermanentDelete($request->salary_advance_id);

                return response()->json('Salary advance are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function permanentDelete($id)
    {
        $salaryAdvance = $this->salaryAdvanceService->permanentDelete($id);

        return response()->json('Salary advance is permanently deleted successfully');
    }

    public function restore($id)
    {
        $salaryAdvance = $this->salaryAdvanceService->restore($id);

        return response()->json('Salary advance restored successfully');
    }
}
