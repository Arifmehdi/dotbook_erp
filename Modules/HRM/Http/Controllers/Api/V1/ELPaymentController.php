<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\ELPayments\CreateELPaymentsApplicationRequest;
use Modules\HRM\Http\Requests\ELPayments\UpdateELPaymentsApplicationRequest;
use Modules\HRM\Interface\ELPaymentServiceInterface;
use Modules\HRM\Transformers\ELPaymentResource;

class ELPaymentController extends Controller
{
    private $elPaymentService;

    public function __construct(ELPaymentServiceInterface $elPaymentService)
    {
        $this->elPaymentService = $elPaymentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $leaveTypes = ELPaymentResource::collection($this->elPaymentService->all());

        return $leaveTypes;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateELPaymentsApplicationRequest $request)
    {
        $data = $this->elPaymentService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Earned Leave Payment Saved successfully!'])->setStatusCode(201);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {

        $leaveType = ELPaymentResource::make($this->elPaymentService->find($id));

        return $leaveType;
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
        $data = $this->elPaymentService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Earned Leave Payment Updated successfully!'])->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $elPayment = $this->elPaymentService->trash($id);

        return response()->json(['message' => 'Earned Leave Payment deleted successfully'])->setStatusCode(202);
    }

    /**
     * Permanent Delete the leaveType Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $elPayment = ELPaymentResource::collection($this->elPaymentService->getTrashedItem());

        return $elPayment;
    }

    /**
     * Permanent Delete the leaveType Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $elPayment = $this->elPaymentService->permanentDelete($id);

        return response()->json(['message' => 'Earned Leave Payment is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $elPayment = $this->elPaymentService->restore($id);

        return response()->json(['message' => 'Earned Leave Payment restored successfully']);
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
                $elPayment = $this->elPaymentService->bulkTrash($request->el_payment_id);

                return response()->json(['message' => 'Earned Leave Payment are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $elPayment = $this->elPaymentService->bulkRestore($request->el_payment_id);

                return response()->json(['message' => 'Earned Leave Payment are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $elPayment = $this->elPaymentService->bulkPermanentDelete($request->el_payment_id);

                return response()->json(['message' => 'Earned Leave Payment are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
