<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\ELPayments\CreateELPaymentsApplicationRequest;
use Modules\HRM\Http\Requests\ELPayments\UpdateELPaymentsApplicationRequest;
use Modules\HRM\Interface\ELPaymentServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\PaymentTypesServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class ELPaymentController extends Controller
{
    private $elPaymentService;

    private $employeeService;

    private $paymentTypeService;

    public function __construct(EmployeeServiceInterface $employeeService, ELPaymentServiceInterface $elPaymentService, PaymentTypesServiceInterface $paymentTypeService)
    {
        $this->elPaymentService = $elPaymentService;
        $this->employeeService = $employeeService;
        $this->paymentTypeService = $paymentTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $employees = $this->employeeService->employeeActiveListWithId();
        $paymentTypes = $this->paymentTypeService->allowedPayment();

        if ($request->showTrashed == 'true') {
            $el_payments = $this->elPaymentService->getTrashedItem();
        } else {
            $el_payments = $this->elPaymentService->all();
        }
        $rowCount = $this->elPaymentService->getRowCount();
        $trashedCount = $this->elPaymentService->getTrashedCount();
        if ($request->ajax()) {
            return DataTables::of($el_payments)
                ->addIndexColumn()
                ->addColumn('employee_id', function ($row) {
                    return $row->employee->employee_id ?? 'No Employee ID';
                })
                ->addColumn('employeeName', function ($row) {
                    return $row->employee->name ?? 'No Employee';
                })
                ->addColumn('payment_date', function ($row) {
                    return Carbon::parse($row->payment_date)->format('Y-m-d');
                })
                ->addColumn('joiningDate', function ($row) {
                    return $row->joining_date ?? 'No Joining Date';
                })
                ->addColumn('paymentType', function ($row) {
                    return $row->paymentType->name ?? 'No Payment Type';
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                <input type="checkbox" name="el_payment_id[]" value="'.$row->id.'" class="mt-2 check1">
                            </div>';

                    return $html;
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

                    if (auth()->user()->can('hrm_el_payments_update')) {
                        $html .= '<a href="'.route('hrm.el-payments.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_el_payments" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_el_payments_delete')) {
                        $html .= '<a href="'.route('hrm.el-payments.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_el_payments" title="Delete">'.$icon2.'</a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge bg-primary text-white">Allowed</span>' : '<span class="badge bg-info text-white">Not-Allowed</span>';
                })
                ->rawColumns(['action', 'check', 'status', 'employee_id', 'paymentTypeName'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::el_payments.index', compact('employees', 'paymentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('hrm::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateELPaymentsApplicationRequest $request)
    {
        $elPayment = $this->elPaymentService->store($request->validated());

        return response()->json('Earned Leave Payment created successfully');

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
    public function edit($id)
    {
        $employees = $this->employeeService->activeEmployee();
        $elPayment = $this->elPaymentService->find($id);
        $paymentTypes = $this->paymentTypeService->allowedPayment();

        return view('hrm::el_payments.ajax_views.edit', compact('employees', 'elPayment', 'paymentTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateELPaymentsApplicationRequest $request, $id)
    {
        $this->elPaymentService->update($request->validated(), $id);

        return response()->json('Earned Leave Payment updated successfully');
    }

    public function permanentDelete($id)
    {
        $leaveType = $this->elPaymentService->permanentDelete($id);

        return response()->json('Leave Type is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $elPayment = $this->elPaymentService->trash($id);

        return response()->json('Earned Leave Payment deleted successfully');
    }

    public function restore($id)
    {
        $leaveType = $this->elPaymentService->restore($id);

        return response()->json('Earned Leave Payment restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->el_payment_id)) {
            if ($request->action_type == 'move_to_trash') {
                $leaveType = $this->elPaymentService->bulkTrash($request->el_payment_id);

                return response()->json('Earned Leave Payment are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $leaveType = $this->elPaymentService->bulkRestore($request->el_payment_id);

                return response()->json('Earned Leave Payment are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $leaveType = $this->elPaymentService->bulkPermanentDelete($request->el_payment_id);

                return response()->json('Earned Leave Payment are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
