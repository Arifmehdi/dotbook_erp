<?php

namespace Modules\CRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\CRM\Http\Requests\Appointment\AppointmentStoreRequest;
use Modules\CRM\Http\Requests\Appointment\AppointmentUpdateRequest;
use Modules\CRM\Interfaces\AppointmentServiceInterface;
use Modules\CRM\Transformers\AppointmentResource;

class AppointmentController extends Controller
{
    private $appointmentService;

    public function __construct(AppointmentServiceInterface $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $appointments = $this->appointmentService->all();
        $appointments = AppointmentResource::collection($appointments);

        return response()->json(['data' => $appointments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(AppointmentStoreRequest $request)
    {
        $appointment = $this->appointmentService->store($request->validated());

        return response()->json([
            'message' => 'Appointment Stored Successfully',
            'data' => $appointment,
        ])->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(AppointmentUpdateRequest $request, $id)
    {
        //Permission
        $appointment = $this->appointmentService->update($request->validated(), $id);

        return response()->json([
            'message' => 'Appointment Updated Successfully',
            'data' => $appointment,
        ])->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $appointment = $this->appointmentService->destroy($id);

        return response()->json([
            'data' => $appointment,
            'message' => 'Appointment Deleted Successfully',
        ])->setStatusCode(200);
    }

    public function show($id)
    {
        $appointment = $this->appointmentService->find($id);

        return AppointmentResource::make($appointment);
    }
}
