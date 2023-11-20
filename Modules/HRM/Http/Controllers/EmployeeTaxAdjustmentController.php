<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\EmployeeTaxAdjustment\CreateEmployeeTaxAdjustmentRequest;
use Modules\HRM\Http\Requests\EmployeeTaxAdjustment\UpdateEmployeeTaxAdjustmentRequest;
use Modules\HRM\Interface\ArrivalServiceInterface;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\EmployeeTaxAdjustmentServiceInterface;
use Yajra\DataTables\DataTables;

class EmployeeTaxAdjustmentController extends Controller
{
    private $employeeTaxAdjustmentService;

    private $commonService;

    private $arrivalService;

    public function __construct(EmployeeTaxAdjustmentServiceInterface $employeeTaxAdjustmentService, CommonServiceInterface $commonService, ArrivalServiceInterface $arrivalService)
    {
        $this->employeeTaxAdjustmentService = $employeeTaxAdjustmentService;
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
            $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->getTrashedItem();
        } else {
            $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->employeeFilter($request);
        }

        $rowCount = $this->employeeTaxAdjustmentService->getRowCount();
        $trashedCount = $this->employeeTaxAdjustmentService->getTrashedCount();

        if ($request->ajax()) {

            return DataTables::of($employeeTaxAdjustment)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="tax_id[]" value="'.$row->id.'" class="mt-2 check1">
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

                    if (auth()->user()->can('hrm_employeeTaxAdjustments_update')) {
                        $html .= '<a href="'.route('hrm.employee-tax-adjustments.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_employeeTaxAdjustments" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_employeeTaxAdjustments_delete')) {
                        $html .= '<a href="'.route('hrm.employee-tax-adjustments.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_employeeTaxAdjustments" title="Delete">'.$icon2.'</a>';
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

        return view('hrm::salary_adjustment.tax_adjustment.addition_deduction.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create(Request $request)
    {
        $employees = $this->arrivalService->activeEmployeeFilter($request);

        return view('hrm::salary_adjustment.tax_adjustment.addition_deduction.ajax_views.add', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateEmployeeTaxAdjustmentRequest $request)
    {
        $this->employeeTaxAdjustmentService->store($request->validated());

        return response()->json('Tax Adjustment added successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit(Request $request, $id)
    {
        $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->find($id);
        $employees = $this->arrivalService->activeEmployeeFilter($request);

        return view('hrm::salary_adjustment.tax_adjustment.addition_deduction.ajax_views.edit', compact('employeeTaxAdjustment', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateEmployeeTaxAdjustmentRequest $request, $id)
    {

        $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->Update($request->validated(), $id);

        return response()->json('Tax Adjustment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->trash($id);

        return response()->json('Tax adjustment deleted successfully');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->tax_id)) {
            if ($request->action_type == 'move_to_trash') {
                $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->bulkTrash($request->tax_id);

                return response()->json('Tax adjustment are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->bulkRestore($request->tax_id);

                return response()->json('Tax adjustment are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->bulkPermanentDelete($request->tax_id);

                return response()->json('Tax adjustment are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function permanentDelete($id)
    {
        $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->permanentDelete($id);

        return response()->json('Tax adjustment is permanently deleted successfully');
    }

    public function restore($id)
    {
        $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->restore($id);

        return response()->json('Tax adjustment restored successfully');
    }
}
