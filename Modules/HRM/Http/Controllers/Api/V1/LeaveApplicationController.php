<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\HRM\Http\Requests\LeaveApplication\CreateLeaveApplicationRequest;
use Modules\HRM\Http\Requests\LeaveApplication\UpdateLeaveApplicationRequest;
use Modules\HRM\Interface\LeaveApplicationServiceInterface;
use Modules\HRM\Transformers\LeaveApplicationResource;

class LeaveApplicationController extends Controller
{
    private $leaveApplicationService;

    public function __construct(LeaveApplicationServiceInterface $leaveApplicationService)
    {
        $this->leaveApplicationService = $leaveApplicationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $leaveApplication = LeaveApplicationResource::collection($this->leaveApplicationService->all());

        return $leaveApplication;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateLeaveApplicationRequest $request)
    {
        $data = $this->leaveApplicationService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Leave application Saved successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $leaveApplication = LeaveApplicationResource::make($this->leaveApplicationService->find($id));

        return $leaveApplication;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateLeaveApplicationRequest $request, $id)
    {
        $data = $this->leaveApplicationService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Leave application Updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $leaveApplication = $this->leaveApplicationService->trash($id);

        return response()->json(['message' => 'Leave application Deleted successfully']);
    }

    /**
     * Permanent Delete the holiday Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $leaveApplication = LeaveApplicationResource::collection($this->leaveApplicationService->getTrashedItem());

        return $leaveApplication;
    }

    /**
     * Permanent Delete the holiday Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $leaveApplication = $this->leaveApplicationService->permanentDelete($id);

        return response()->json(['message' => 'Leave application is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $leaveApplication = $this->leaveApplicationService->restore($id);

        return response()->json(['message' => 'Leave application restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->leave_application_id)) {
            if ($request->action_type == 'move_to_trash') {
                $holiday = $this->leaveApplicationService->bulkTrash($request->leave_application_id);

                return response()->json(['message' => 'Leave applications are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $holiday = $this->leaveApplicationService->bulkRestore($request->leave_application_id);

                return response()->json(['message' => 'Leave applications are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $holiday = $this->leaveApplicationService->bulkPermanentDelete($request->leave_application_id);

                return response()->json(['message' => 'Leave application are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function employeeFilter(Request $request, Response $response)
    {

    }
}
