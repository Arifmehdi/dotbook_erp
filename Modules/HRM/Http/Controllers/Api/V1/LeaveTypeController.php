<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\LeaveType\CreateLeaveTypeRequest;
use Modules\HRM\Http\Requests\LeaveType\UpdateLeaveTypeRequest;
use Modules\HRM\Interface\LeaveTypeServiceInterface;
use Modules\HRM\Transformers\LeaveTypeResource;

class LeaveTypeController extends Controller
{
    private $leaveTypeService;

    public function __construct(LeaveTypeServiceInterface $leaveTypeService)
    {
        $this->leaveTypeService = $leaveTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $leaveTypes = LeaveTypeResource::collection($this->leaveTypeService->all());

        return $leaveTypes;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateLeaveTypeRequest $request)
    {
        $data = $this->leaveTypeService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'leave Type Saved successfully!'])->setStatusCode(201);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {

        $leaveType = LeaveTypeResource::make($this->leaveTypeService->find($id));

        return $leaveType;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateLeaveTypeRequest $request, $id)
    {
        $data = $this->leaveTypeService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'leave Type Updated successfully!'])->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $leaveType = $this->leaveTypeService->trash($id);

        return response()->json(['message' => 'leave Type deleted successfully'])->setStatusCode(202);
    }

    /**
     * Permanent Delete the leaveType Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $leaveTypes = LeaveTypeResource::collection($this->leaveTypeService->getTrashedItem());

        return $leaveTypes;
    }

    /**
     * Permanent Delete the leaveType Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $leaveType = $this->leaveTypeService->permanentDelete($id);

        return response()->json(['message' => 'Leave Type is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $leaveType = $this->leaveTypeService->restore($id);

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

        if (isset($request->leave_type_id)) {
            if ($request->action_type == 'move_to_trash') {
                $leaveType = $this->leaveTypeService->bulkTrash($request->leave_type_id);

                return response()->json(['message' => 'Leave Types are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $leaveType = $this->leaveTypeService->bulkRestore($request->leave_type_id);

                return response()->json(['message' => 'Leave Types are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $leaveType = $this->leaveTypeService->bulkPermanentDelete($request->leave_type_id);

                return response()->json(['message' => 'Leave Types are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
