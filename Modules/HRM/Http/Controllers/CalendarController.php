<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\HRM\Entities\Holiday;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        abort_if(! auth()->user()->can('hrm_holidays_calendar_index'), 403, 'Access Forbidden');
        $holidays = Holiday::all();
        $events = [];
        foreach ($holidays as $holiday) {
            $events[] = [
                'id' => $holiday->id,
                'title' => $holiday->name,
                'start' => $holiday->from,
                'end' => $holiday->to,
            ];
        }
        $before_holiday = Holiday::whereDate('from', '<=', date('Y-m-d h:i:s'))->orderByDesc('from')->take(5)->get(['id', 'name', 'from', 'to', 'num_of_days']);
        $after_holiday = Holiday::whereDate('from', '>=', date('Y-m-d h:i:s'))->orderByDesc('from')->take(5)->get(['id', 'name', 'from', 'to', 'num_of_days']);

        return view('hrm::holiday_calendar.holiday_calendar', ['events' => $events, 'asc_holidays' => $before_holiday, 'dsc_holidays' => $after_holiday]);
    }

    public function store(Request $request)
    {
        abort_if(! auth()->user()->can('hrm_holidays_calendar_create'), 403, 'Access Forbidden');
        $request->validate([
            'title' => 'required|string',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $numberOfDays = $startDate->diffInDays($endDate);

        $holiday = Holiday::create([
            'name' => $request->title,
            'type' => ($request->title == 'Friday') ? 'Offday' : 'Holiday',
            'from' => $request->start_date,
            'to' => $request->end_date,
            'num_of_days' => $numberOfDays,
        ]);

        return response()->json($holiday);
    }

    public function show($id)
    {
        return view('hrm::show');
    }

    public function update(Request $request, $id)
    {
        abort_if(! auth()->user()->can('hrm_holidays_calendar_update'), 403, 'Access Forbidden');
        $holiday = Holiday::find($id);
        if (! $holiday) {
            return response()->json([
                'error' => 'Unable to locate the holiday',
            ], 404);
        }
        $holiday->update([
            'from' => $request->start_date,
            'to' => $request->end_date,
        ]);

        return response()->json('Holiday Updated');
    }

    public function destroy($id)
    {
        abort_if(! auth()->user()->can('hrm_holidays_calendar_delete'), 403, 'Access Forbidden');
        $holiday = Holiday::find($id);
        if (! $holiday) {
            return response()->json([
                'error' => 'Unable to locate the holiday',
            ], 404);
        }
        $holiday->delete();

        return $id;
    }
}
