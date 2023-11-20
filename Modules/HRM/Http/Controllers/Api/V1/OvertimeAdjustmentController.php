<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\HRM\Http\Requests\OvertimeAdjustment\CreateOvertimeAdjustmentRequest;
use Modules\HRM\Http\Requests\OvertimeAdjustment\UpdateOvertimeAdjustmentRequest;
use Modules\HRM\Interface\OvertimeAdjustmentServiceInterface;
use Modules\HRM\Transformers\OvertimeAdjustmentResource;

class OvertimeAdjustmentController extends Controller
{
    private $overtimeAdjustmentService;

    public function __construct(OvertimeAdjustmentServiceInterface $overtimeAdjustmentService)
    {
        $this->overtimeAdjustmentService = $overtimeAdjustmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $overtimeAdjustment = OvertimeAdjustmentResource::collection($this->overtimeAdjustmentService->all());

        return $overtimeAdjustment;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateOvertimeAdjustmentRequest $request)
    {
        $data = $this->overtimeAdjustmentService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Over time adjustment saved successfully.']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $overtimeAdjustment = OvertimeAdjustmentResource::make($this->overtimeAdjustmentService->find($id));

        return $overtimeAdjustment;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateOvertimeAdjustmentRequest $request, $id)
    {
        $data = $this->overtimeAdjustmentService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Over Time adjustment Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $overtimeAdjustment = $this->overtimeAdjustmentService->trash($id);

        return response()->json(['message' => 'Over time adjustment Deleted Successfully.']);

    }

    public function allTrash()
    {
        $overtimeAdjustment = OvertimeAdjustmentResource::collection($this->overtimeAdjustmentService->getTrashedItem());

        return $overtimeAdjustment;
    }

    public function permanentDelete($id)
    {
        $overtimeAdjustment = $this->overtimeAdjustmentService->permanentDelete($id);

        return response()->json(['message' => 'Over time adjustment is permanent deleted successfully']);
    }

    public function restore($id)
    {
        $overtimeAdjustment = $this->overtimeAdjustmentService->restore($id);

        return response()->json(['message' => 'Over time adjustment restored Successfully']);
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->overtime_id)) {
            if ($request->action_type == 'move_to_trash') {
                $overtimeAdjustment = $this->overtimeAdjustmentService->bulkTrash($request->overtime_id);

                return response()->json(['message' => 'Over time adjustment are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $overtimeAdjustment = $this->overtimeAdjustmentService->bulkRestore($request->overtime_id);

                return response()->json(['message' => 'Over time adjustment are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $overtimeAdjustment = $this->overtimeAdjustmentService->bulkPermanentDelete($request->overtime_id);

                return response()->json(['message' => 'Over time adjustment are permanent deleted successfully'], 401);
            } else {
                return response()->json(['message' => 'Action is not specified']);
            }
        } else {
            return response()->json(['message' => 'No item is selected'], 401);
        }
    }
}
