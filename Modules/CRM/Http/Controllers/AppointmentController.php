<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Http\Requests\Appointment\AppointmentStoreRequest;
use Modules\CRM\Http\Requests\Appointment\AppointmentUpdateRequest;
use Modules\CRM\Interfaces\AppointmentServiceInterface;
use Yajra\DataTables\Facades\DataTables;

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
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $appointments = $this->appointmentService->all();

            // $appointments = DB::table('appointments')->orderBy('id', 'desc')->get();
            // $appointments = DB::table('appointments')->orderBy('schedule_date', 'asc')->orderBy('schedule_time', 'desc')->get();

            // \Log::debug($appointments);
            return DataTables::of($appointments)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="'.route('crm.appointment.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('crm.appointment.destroy', $row->id).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->addColumn('appointors', function ($row) {
                    $f_name = User::find($row->appointor_id)->name;
                    $l_name = User::find($row->appointor_id)->last_name;

                    return $f_name.' '.$l_name;
                })
                ->addColumn('customers', function ($row) {
                    $name = Customer::find($row->customer_id)?->name ?? 'N/A';

                    return $name;
                })
                ->addColumn('schedule', function ($row) {

                    $day = date('d', strtotime($row->schedule_date));
                    $week = date('D', strtotime($row->schedule_date));
                    $year = date('Y', strtotime($row->schedule_date));
                    $month = date('M', strtotime($row->schedule_date));
                    $time = $row->schedule_time;

                    return $week.', '.$day.' '.$month.' '.$year.' '.$time;
                })
                ->addColumn('status', function ($row) {
                    $date1 = Carbon::now();
                    $date2 = Carbon::parse($row->schedule_date);
                    $diff = $date2->diffInDays($date1);

                    if ($date1 < $date2) {
                        return '<span class="badge rounded-pill bg-primary">Upcoming</span>';
                    } elseif ($diff == 0) {

                        $time2 = Carbon::now()->format('h:i:s');

                        $time1 = Carbon::parse($row->schedule_time);

                        if ($time1->greaterThan($time2)) {
                            return '<span class="badge rounded-pill bg-primary">Upcoming</span>';
                        } else {
                            return '<span class="badge rounded-pill bg-danger">Done</span>';
                        }
                    } else {
                        return '<span class="badge rounded-pill bg-danger">Done</span>';
                    }
                })
                ->rawColumns(['action', 'status', 'appointors', 'customers', 'schedule'])
                ->smart(true)
                ->make(true);
        }

        $users = User::all();
        $customers = Customer::all();

        return view('crm::appointment.index', compact('customers', 'users'));
    }

    public function create()
    {

        return view('crm::create');
    }

    public function store(AppointmentStoreRequest $request)
    {
        $appointment = $this->appointmentService->store($request);

        return response()->json('Appointment created successfully');
    }

    public function show($id)
    {
        return view('crm::show');
    }

    public function edit(Request $request, $id)
    {
        $appointment = $this->appointmentService->find($id);
        $users = User::all();
        $customers = Customer::all();

        return view('crm::appointment.ajax_view.edit', compact('appointment', 'users', 'customers'));
    }

    public function update(AppointmentUpdateRequest $request, $id)
    {
        $this->appointmentService->update($request, $id);

        return response()->json('Appointment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->appointmentService->destroy($id);

        return response()->json('Appointment deleted successfully');
    }
}
