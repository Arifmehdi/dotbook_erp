<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\HRM\Http\Requests\Holiday\CreateHolidayRequest;
use Modules\HRM\Http\Requests\Holiday\UpdateHolidayRequest;
use Modules\HRM\Interface\HolidayServiceInterface;
use Modules\HRM\Transformers\HolidayResource;

class HolidayController extends Controller
{
    private $holidayService;

    public function __construct(HolidayServiceInterface $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $departments = HolidayResource::collection($this->holidayService->all());

        return $departments;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateHolidayRequest $request)
    {
        $data = $this->holidayService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Holiday Saved successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $department = HolidayResource::make($this->holidayService->find($id));

        return $department;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateHolidayRequest $request, $id)
    {
        $data = $this->holidayService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Holiday Updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $department = $this->holidayService->trash($id);

        return response()->json(['message' => 'Holiday Deleted successfully']);
    }

    /**
     * Permanent Delete the holiday Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $holidays = HolidayResource::collection($this->holidayService->getTrashedItem());

        return $holidays;
    }

    /**
     * Permanent Delete the holiday Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $holiday = $this->holidayService->permanentDelete($id);

        return response()->json(['message' => 'Holiday is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $holiday = $this->holidayService->restore($id);

        return response()->json(['message' => 'Holiday restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->holiday_id)) {
            if ($request->action_type == 'move_to_trash') {
                $holiday = $this->holidayService->bulkTrash($request->holiday_id);

                return response()->json(['message' => 'Holidays are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $holiday = $this->holidayService->bulkRestore($request->holiday_id);

                return response()->json(['message' => 'Holidays are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $holiday = $this->holidayService->bulkPermanentDelete($request->holiday_id);

                return response()->json(['message' => 'Holidays are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
