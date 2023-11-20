<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\SalaryAdjustment\CreateSalaryAdjustmentRequest;
use Modules\HRM\Http\Requests\SalaryAdjustment\UpdateSalaryAdjustmentRequest;
use Modules\HRM\Interface\ArrivalServiceInterface;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\SalaryAdjustmentServiceInterface;
use Yajra\DataTables\DataTables;

class SalaryAdjustmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    private $salaryAdjustmentService;

    private $arrivalService;

    private $commonService;

    public function __construct(ArrivalServiceInterface $arrivalService, CommonServiceInterface $commonService, SalaryAdjustmentServiceInterface $salaryAdjustmentService)
    {
        $this->arrivalService = $arrivalService;
        $this->commonService = $commonService;
        $this->salaryAdjustmentService = $salaryAdjustmentService;
    }

    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $salaryAdjustment = $this->salaryAdjustmentService->getTrashedItem();
        } else {
            $salaryAdjustment = $this->salaryAdjustmentService->employeeFilter($request);
        }

        $rowCount = $this->salaryAdjustmentService->getRowCount();
        $trashedCount = $this->salaryAdjustmentService->getTrashedCount();

        if ($request->ajax()) {

            return DataTables::of($salaryAdjustment)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="employee_id[]" value="'.$row->id.'" class="mt-2 check1">
                    </div>';

                    return $html;
                })
                ->editColumn('employeeId', function ($row) {
                    return $row->employee->employee_id ?? 'Employee Id is not Specified';
                })
                ->editColumn('name', function ($row) {
                    return $row->employee->name ?? 'Employee name is not Specified';
                })
                ->editColumn('month', function ($row) {
                    return $row->MonthName ?? 'Month is not Specified';
                })
                ->editColumn('type', function ($row) {
                    return ($row->type == 1) ? '✅ Addition' : '🛑 Deduction' ?? 'Employment type is not Specified';
                })
                ->editColumn('photo', function ($row) {
                    return $this->commonService->showAvatarImage('uploads/employees/', $row->photo);
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

                    if (auth()->user()->can('hrm_salaryAdjustments_update')) {
                        $html .= '<a href="'.route('hrm.salaryAdjustments.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_salaryAdjustments" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_salaryAdjustments_delete')) {
                        $html .= '<a href="'.route('hrm.salaryAdjustments.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_salaryAdjustments" title="Delete">'.$icon2.'</a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                // ->editColumn('type', function ($row) {
                //     return $row->type == 1 ? '<span class="badge bg-primary text-white">Addition</span>' : '<span class="badge bg-info text-white">Deduction</span>';
                // })
                ->rawColumns(['action', 'check', 'employee', 'section', 'photo', 'type'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::salary_adjustment.addition_deduction.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create(Request $request)
    {
        $employees = $this->arrivalService->activeEmployeeFilter($request);

        return view('hrm::salary_adjustment.addition_deduction.ajax_views.add', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateSalaryAdjustmentRequest $request)
    {
        $this->salaryAdjustmentService->store($request->validated());

        return response()->json('Salary Adjustment added successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('hrm::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit(Request $request, $id)
    {
        $salaryAdjustment = $this->salaryAdjustmentService->find($id);
        $employees = $this->arrivalService->activeEmployeeFilter($request);

        return view('hrm::salary_adjustment.addition_deduction.ajax_views.edit', compact('salaryAdjustment', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateSalaryAdjustmentRequest $request, $id)
    {

        $salaryAdjustment = $this->salaryAdjustmentService->Update($request->validated(), $id);

        return response()->json('Salary Adjustment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $salaryAdjustment = $this->salaryAdjustmentService->trash($id);

        return response()->json('Salary adjustment deleted successfully');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->employee_id)) {
            if ($request->action_type == 'move_to_trash') {
                $salaryAdjustment = $this->salaryAdjustmentService->bulkTrash($request->employee_id);

                return response()->json('Salary adjustment are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $salaryAdjustment = $this->salaryAdjustmentService->bulkRestore($request->employee_id);

                return response()->json('Salary adjustment are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $salaryAdjustment = $this->salaryAdjustmentService->bulkPermanentDelete($request->employee_id);

                return response()->json('Salary adjustment are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function permanentDelete($id)
    {
        $salaryAdjustment = $this->salaryAdjustmentService->permanentDelete($id);

        return response()->json('Salary adjustment is permanently deleted successfully');
    }

    public function restore($id)
    {
        $salaryAdjustment = $this->salaryAdjustmentService->restore($id);

        return response()->json('Salary adjustment restored successfully');
    }
}
