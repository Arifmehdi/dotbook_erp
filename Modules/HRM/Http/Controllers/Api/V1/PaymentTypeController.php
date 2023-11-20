<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\PaymentType\CreatePaymentTypeApplicationRequest;
use Modules\HRM\Http\Requests\PaymentType\UpdatePaymentTypeApplicationRequest;
use Modules\HRM\Interface\PaymentTypesServiceInterface;
use Modules\HRM\Transformers\PaymentTypeResource;

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
    public function index()
    {
        $paymentType = PaymentTypeResource::collection($this->paymentTypeService->all());

        return $paymentType;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreatePaymentTypeApplicationRequest $request)
    {
        $data = $this->paymentTypeService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Payment Type Saved successfully!'])->setStatusCode(201);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        // return ('ok');
        $paymentType = PaymentTypeResource::make($this->paymentTypeService->find($id));

        return $paymentType;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('hrm::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdatePaymentTypeApplicationRequest $request, $id)
    {
        $data = $this->paymentTypeService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Payment Type Updated successfully!'])->setStatusCode(202);
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

        return response()->json(['message' => 'Payment Type deleted successfully'])->setStatusCode(202);
    }

    public function allTrash()
    {
        $paymentTypes = PaymentTypeResource::collection($this->paymentTypeService->getTrashedItem());

        return $paymentTypes;
    }

    /**
     * Permanent Delete the leaveType Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $paymentTypes = $this->paymentTypeService->permanentDelete($id);

        return response()->json(['message' => 'Payment Type is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $paymentTypes = $this->paymentTypeService->restore($id);

        return response()->json(['message' => 'Leave Type restored successfully']);
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
                $leaveType = $this->paymentTypeService->bulkTrash($request->payment_type_id);

                return response()->json(['message' => 'Payment Types are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $leaveType = $this->paymentTypeService->bulkRestore($request->payment_type_id);

                return response()->json(['message' => 'Payment Types are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $leaveType = $this->paymentTypeService->bulkPermanentDelete($request->payment_type_id);

                return response()->json(['message' => 'Payment Types are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
