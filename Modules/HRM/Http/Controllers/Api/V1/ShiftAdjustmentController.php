<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\HRM\Http\Requests\ShiftAdjustment\CreateShiftAdjustmentRequest;
use Modules\HRM\Http\Requests\ShiftAdjustment\UpdateShiftAdjustmentRequest;
use Modules\HRM\Interface\ShiftAdjustmentServiceInterface;
use Modules\HRM\Transformers\ShiftAdjustmentResource;

class ShiftAdjustmentController extends Controller
{
    private $shiftAdjustmentService;

    public function __construct(ShiftAdjustmentServiceInterface $shiftAdjustmentService)
    {
        $this->shiftAdjustmentService = $shiftAdjustmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $designations = ShiftAdjustmentResource::collection($this->shiftAdjustmentService->all());

        return $designations;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateShiftAdjustmentRequest $request)
    {
        $data = $this->shiftAdjustmentService->store($request);

        return response()->json(['data' => $data, 'message' => 'Shift Adjustment Saved successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $designation = ShiftAdjustmentResource::make($this->shiftAdjustmentService->find($id));

        return $designation;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateShiftAdjustmentRequest $request, $id)
    {
        $data = $this->shiftAdjustmentService->update($request, $id);

        return response()->json(['data' => $data, 'message' => 'Shift Adjustment Updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $designation = $this->shiftAdjustmentService->trash($id);

        return response()->json(['message' => 'Shift Adjustment Deleted successfully']);
    }

    /**
     * Permanent Delete the Shift Adjustment Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $shiftAdjustments = ShiftAdjustmentResource::collection($this->shiftAdjustmentService->getTrashedItem());

        return $shiftAdjustments;
    }

    /**
     * Permanent Delete the shiftAdjustment Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $shiftAdjustment = $this->shiftAdjustmentService->permanentDelete($id);

        return response()->json(['message' => 'Shift Adjustment is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $shiftAdjustment = $this->shiftAdjustmentService->restore($id);

        return response()->json(['message' => 'Shift Adjustment restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->shiftAdjustment_id)) {
            if ($request->action_type == 'move_to_trash') {
                $shiftAdjustment = $this->shiftAdjustmentService->bulkTrash($request->shiftAdjustment_id);

                return response()->json(['message' => 'Shift Adjustments are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $shiftAdjustment = $this->shiftAdjustmentService->bulkRestore($request->shiftAdjustment_id);

                return response()->json(['message' => 'Shift Adjustments are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $shiftAdjustment = $this->shiftAdjustmentService->bulkPermanentDelete($request->shiftAdjustment_id);

                return response()->json(['message' => 'Shift Adjustments are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
