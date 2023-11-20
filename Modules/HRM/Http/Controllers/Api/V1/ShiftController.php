<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\Shift;
use Modules\HRM\Enums\EmploymentStatus;
use Modules\HRM\Http\Requests\Shift\CreateShiftRequest;
use Modules\HRM\Http\Requests\Shift\UpdateShiftRequest;
use Modules\HRM\Interface\ShiftServiceInterface;
use Modules\HRM\Transformers\shiftChangeResource;
use Modules\HRM\Transformers\ShiftResource;

class ShiftController extends Controller
{
    private $shiftService;

    public function __construct(ShiftServiceInterface $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $shifts = ShiftResource::collection($this->shiftService->all());

        return $shifts;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateShiftRequest $request)
    {
        $data = $this->shiftService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Shift Saved successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $shift = ShiftResource::make($this->shiftService->find($id));

        return $shift;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateShiftRequest $request, $id)
    {
        $data = $this->shiftService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Shift Updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $shift = $this->shiftService->trash($id);

        return response()->json(['message' => 'Shift Deleted successfully']);
    }

    /**
     * Permanent Delete the shift Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $shifts = ShiftResource::collection($this->shiftService->getTrashedItem());

        return $shifts;
    }

    /**
     * Permanent Delete the shift Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $shift = $this->shiftService->permanentDelete($id);

        return response()->json(['message' => 'Shift is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $shift = $this->shiftService->restore($id);

        return response()->json(['message' => 'Shift restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->shift_id)) {
            if ($request->action_type == 'move_to_trash') {
                $shift = $this->shiftService->bulkTrash($request->shift_id);

                return response()->json(['message' => 'Shifts are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $shift = $this->shiftService->bulkRestore($request->shift_id);

                return response()->json(['message' => 'Shifts are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $shift = $this->shiftService->bulkPermanentDelete($request->shift_id);

                return response()->json(['message' => 'Shifts are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function shiftChange(Request $request)
    {
        $employees = Employee::where('employment_status', EmploymentStatus::Active)->get();
        $employee = shiftChangeResource::collection($employees);

        return response()->json([
            'employee' => $employee,
        ]);
    }

    public function shiftChangeById($id, $employee_id)
    {
        $employee = Employee::where('id', $employee_id)->update(['shift_id' => $id]);

        return response()->json('Shift changed successfully!');
    }
}
