<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\OvertimeAdjustment\CreateOvertimeAdjustmentRequest;
use Modules\HRM\Http\Requests\OvertimeAdjustment\UpdateOvertimeAdjustmentRequest;
use Modules\HRM\Interface\ArrivalServiceInterface;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\OvertimeAdjustmentServiceInterface;
use Yajra\DataTables\DataTables;

class OvertimeAdjustmentController extends Controller
{
    private $overtimeAdjustmentService;

    private $arrivalService;

    private $commonService;

    public function __construct(ArrivalServiceInterface $arrivalService, CommonServiceInterface $commonService, OvertimeAdjustmentServiceInterface $overtimeAdjustmentService)
    {
        $this->arrivalService = $arrivalService;
        $this->commonService = $commonService;
        $this->overtimeAdjustmentService = $overtimeAdjustmentService;
    }

    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $overtimeAdjustmentService = $this->overtimeAdjustmentService->getTrashedItem();
        } else {
            $overtimeAdjustmentService = $this->overtimeAdjustmentService->employeeFilter($request);
        }

        $rowCount = $this->overtimeAdjustmentService->getRowCount();
        $trashedCount = $this->overtimeAdjustmentService->getTrashedCount();
        if ($request->ajax()) {

            return DataTables::of($overtimeAdjustmentService)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="overtime_id[]" value="'.$row->id.'" class="mt-2 check1">
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
                ->editColumn('otMinutes', function ($row) {
                    return $row->OtMinutesName ?? 'Month is not Specified';
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
                        $html .= '<a href="'.route('hrm.overtimeAdjustments.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_salaryAdjustments" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_salaryAdjustments_delete')) {
                        $html .= '<a href="'.route('hrm.overtimeAdjustments.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_salaryAdjustments" title="Delete">'.$icon2.'</a>';
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

        return view('hrm::salary_adjustment.overtime_adjustment.addition_deduction.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create(Request $request)
    {
        $employees = $this->arrivalService->activeEmployeeFilter($request);

        return view('hrm::salary_adjustment.overtime_adjustment.addition_deduction.ajax_views.add', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateOvertimeAdjustmentRequest $request)
    {
        $this->overtimeAdjustmentService->store($request->validated());

        return response()->json('Over time Adjustment added successfully');
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
        $overtimeAdjustment = $this->overtimeAdjustmentService->find($id);
        $employees = $this->arrivalService->activeEmployeeFilter($request);

        return view('hrm::salary_adjustment.overtime_adjustment.addition_deduction.ajax_views.edit', compact('overtimeAdjustment', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateOvertimeAdjustmentRequest $request, $id)
    {

        $overtimeAdjustment = $this->overtimeAdjustmentService->Update($request->validated(), $id);

        return response()->json('Over time Adjustment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $salaryAdjustment = $this->overtimeAdjustmentService->trash($id);

        return response()->json('Over time adjustment deleted successfully');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->overtime_id)) {
            if ($request->action_type == 'move_to_trash') {
                $overtimeAdjustment = $this->overtimeAdjustmentService->bulkTrash($request->overtime_id);

                return response()->json('Over time adjustment are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $overtimeAdjustment = $this->overtimeAdjustmentService->bulkRestore($request->overtime_id);

                return response()->json('Over time adjustment are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $overtimeAdjustment = $this->overtimeAdjustmentService->bulkPermanentDelete($request->overtime_id);

                return response()->json('Over time adjustment are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function permanentDelete($id)
    {
        $salaryAdjustment = $this->overtimeAdjustmentService->permanentDelete($id);

        return response()->json('Salary adjustment is permanently deleted successfully');
    }

    public function restore($id)
    {
        $overtimeAdjustment = $this->overtimeAdjustmentService->restore($id);

        return response()->json('Salary adjustment restored successfully');
    }
}
