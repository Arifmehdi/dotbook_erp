<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\PaymentType\CreatePaymentTypeApplicationRequest;
use Modules\HRM\Http\Requests\PaymentType\UpdatePaymentTypeApplicationRequest;
use Modules\HRM\Interface\PaymentTypesServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class PaymentTypeController extends Controller
{
    private $paymentTypeService;

    public function __construct(PaymentTypesServiceInterface $paymentTypeService)
    {
        $this->paymentTypeService = $paymentTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $paymentTypes = $this->paymentTypeService->getTrashedItem();
        } else {
            $paymentTypes = $this->paymentTypeService->all();
        }

        $rowCount = $this->paymentTypeService->getRowCount();
        $trashedCount = $this->paymentTypeService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($paymentTypes)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->status == 1) {
                        $status = 'Active';
                    } else {
                        $status = 'Disable';
                    }

                    return $status;
                })

                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="payment_type_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';

                    return $html;
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge bg-primary text-white">Allowed</span>' : '<span class="badge bg-info text-white">Not-Allowed</span>';
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

                    if (auth()->user()->can('hrm_payments_types_update')) {
                        $html .= '<a href="'.route('hrm.payment-types.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_leave_type" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_payments_types_delete')) {
                        $html .= '<a href="'.route('hrm.payment-types.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_leave_type" title="Delete">'.$icon2.'</a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check', 'status'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::payment_types.index', compact('paymentTypes'));
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
    public function store(CreatePaymentTypeApplicationRequest $request)
    {
        $service = $this->paymentTypeService->store($request->validated());

        return response()->json('Payment Type created successfully');
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
        $paymentType = $this->paymentTypeService->find($id);

        return view('hrm::payment_types.ajax_views.edit', compact('paymentType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(updatePaymentTypeApplicationRequest $request, $id)
    {
        $paymentType = $this->paymentTypeService->update($request->validated(), $id);

        return response()->json('Payment Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $paymentType = $this->paymentTypeService->trash($id);

        return response()->json('Payment Type deleted successfully');
    }

    public function permanentDelete($id)
    {
        $paymentType = $this->paymentTypeService->permanentDelete($id);

        return response()->json('Leave Type is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $paymentType = $this->paymentTypeService->restore($id);

        return response()->json('Leave Type restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->payment_type_id)) {
            if ($request->action_type == 'move_to_trash') {
                $paymentType = $this->paymentTypeService->bulkTrash($request->payment_type_id);

                return response()->json('Payment Types are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $paymentType = $this->paymentTypeService->bulkRestore($request->payment_type_id);

                return response()->json('Payment Types are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $paymentType = $this->paymentTypeService->bulkPermanentDelete($request->payment_type_id);

                return response()->json('Payment Types are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
