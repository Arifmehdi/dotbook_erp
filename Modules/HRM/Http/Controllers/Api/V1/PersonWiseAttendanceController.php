<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Interface\AttendanceServiceInterface;
use Modules\HRM\Transformers\PersonWiseAttendanceResource;

class PersonWiseAttendanceController extends Controller
{
    private $attendanceService;

    public function __construct(AttendanceServiceInterface $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $attendances = $this->attendanceService->attendanceEmployee($request)->paginate();
        $attendances = PersonWiseAttendanceResource::collection($attendances);

        return $attendances;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $this->attendanceService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Person wise attendance saved successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        $attendance = AttendanceResource::make($this->attendanceService->find($id));

        return $attendance;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $this->attendanceService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Person wise attendance update successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
}
