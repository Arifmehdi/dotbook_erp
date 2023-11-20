<?php

namespace Modules\CRM\Services;

use Modules\CRM\Entities\Appointments;
use Modules\CRM\Interfaces\AppointmentServiceInterface;

class AppointmentService implements AppointmentServiceInterface
{
    public function all()
    {
        $appointments = Appointments::all();

        return $appointments;
    }

    public function store($request)
    {
        $appointment = new Appointments;
        $appointment->schedule_date = date('Y-m-d', strtotime($request->schedule_date));
        $appointment->schedule_time = date('H:i:s', strtotime($request->schedule_time));
        $appointment->customer_id = $request->customer_id;
        $appointment->appointor_id = $request->appointor_id;
        $appointment->description = $request->description;
        $appointment->save();

        return $appointment;
    }

    public function find($id)
    {
        $appointment = Appointments::find($id);

        return $appointment;
    }

    public function update($request, $id)
    {
        $appointments = Appointments::find($id);
        $appointments->schedule_date = date('Y-m-d', strtotime($request->schedule_date));
        $appointments->schedule_time = date('H:i:s', strtotime($request->schedule_time));
        $appointments->customer_id = $request->customer_id;
        $appointments->appointor_id = $request->appointor_id;
        $appointments->description = $request->description;
        $appointments->save();

        return $appointments;
    }

    public function destroy($id)
    {
        $appointments = Appointments::find($id);
        $appointments->delete();

        return $appointments;
    }
}
