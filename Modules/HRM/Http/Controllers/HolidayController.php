<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Event;
use Modules\HRM\Entities\Holiday;
use Modules\HRM\Http\Requests\Holiday\CreateHolidayRequest;
use Modules\HRM\Http\Requests\Holiday\UpdateHolidayRequest;
use Modules\HRM\Http\Requests\HolidayEvents\CreateHolidayEventRequest;
use Modules\HRM\Interface\HolidayEventServiceInterface;
use Modules\HRM\Interface\HolidayServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class HolidayController extends Controller
{
    private $holidayService;

    private $holidayEventService;

    public function __construct(HolidayServiceInterface $holidayService, HolidayEventServiceInterface $holidayEventService)
    {
        $this->holidayService = $holidayService;
        // $this->holidayEventService = $holidayEventService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $holidays = $this->holidayService->getTrashedItem();
        } else {
            $holidays = $this->holidayService->all();
        }

        $rowCount = $this->holidayService->getRowCount();
        $trashedCount = $this->holidayService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($holidays)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="holiday_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';

                    return $html;
                })
                ->editColumn('from', function ($row) {
                    $from_date = date(config('hrm.date_format'), strtotime($row->from));

                    return $from_date;
                })
                ->editColumn('to', function ($row) {
                    $to_date = date(config('hrm.date_format'), strtotime($row->to));

                    return $to_date;
                })
                ->addColumn('action', function ($row) {
                    $action1 = '';
                    $action2 = '';
                    $type = '';
                    $icon2 = '';
                    if ($row->trashed()) {
                        $action1 = 'restore';
                        $action2 = 'permanent-delete';
                        $type = 'restore';
                        $icon1 = '<i class="fa-solid fa-recycle"></i>';
                        $icon2 = '<i class="fa-solid fa-trash-check"></i>';
                    } else {
                        $action1 = 'edit';
                        $action2 = 'destroy';
                        $type = 'Edit';
                        $icon1 = '<span class="fas fa-edit"></span></a>';
                        $icon2 = '<span class="fas fa-trash "></span>';
                    }
                    $html = '<div class="dropdown table-dropdown">';

                    if (auth()->user()->can('hrm_holidays_update')) {
                        $html .= '<a href="'.route('hrm.holidays.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_holidays_update" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_holidays_delete')) {
                        $html .= '<a href="'.route('hrm.holidays.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_holiday" title="Delete">'.$icon2.'</a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::holidays.index', compact('holidays'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateHolidayRequest $request)
    {
        $holiday = $this->holidayService->store($request->validated());

        return response()->json('Holiday created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {

        $holiday = $this->holidayService->find($id);

        return view('hrm::holidays.ajax_views.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateHolidayRequest $request, $id)
    {
        $holiday = $this->holidayService->update($request->validated(), $id);

        return response()->json('Holiday updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $holiday = $this->holidayService->trash($id);

        return response()->json('Holiday deleted successfully');
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

        return response()->json('Holiday is permanently deleted successfully');
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

        return response()->json('Holiday restored successfully');
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

                return response()->json('Holidays are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $holiday = $this->holidayService->bulkRestore($request->holiday_id);

                return response()->json('Holidays are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $holiday = $this->holidayService->bulkPermanentDelete($request->holiday_id);

                return response()->json('Holidays are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    // // public function storeEvent(CreateHolidayEventRequest $request)
    // public function storeEvent(Request $request)
    // {
    //     $holidaysEvent = new Event();
    //     $holidaysEvent->title = $request->title;
    //     $holidaysEvent->start = $request->start;
    //     $holidaysEvent->end = $request->end;
    //     $holidaysEvent->color = $request->color;
    //     $holidaysEvent->save();

    //     // $request->validate([

    //     // ]);
    //     // $holiday = $this->holidayEventService->store($request->validated());
    //     return response()->json('Holiday event created successfully');
    // }
}
