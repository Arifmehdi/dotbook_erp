<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HRM\Entities\Event;

class CalenderController extends Controller
{
    public function index(Request $request)
    {
        return $request->start;
        if ($request->ajax()) {

            // $data = Event::where('start', $request->start)
            $data = Event::whereDate('start', '>=', $request->start)
                ->whereDate('end', '<=', $request->end)
                ->get(['id', 'title', 'start', 'end']);

            return response()->json($data);
        }

        return view('hrm::holiday_calendar.holiday_calendar');
    }

    public function ajax(Request $request)
    {
        return 'view';
        switch ($request->type) {
            case 'add':
                $event = Event::create([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                ]);

                return response()->json($event);
                break;

            case 'update':
                $event = Event::find($request->id)->update([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                ]);

                return response()->json($event);
                break;

            case 'delete':
                $event = Event::find($request->id)->delete();

                return response()->json($event);
                break;

            default:
                // code...
                break;
        }
    }
}
