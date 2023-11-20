<?php

namespace App\Http\Controllers;

use App\Models\StockEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockIssueEventsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $events = DB::table('stock_events')->orderBy('id', 'DESC')->get();

            return DataTables::of($events)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="'.route('stock.issue.events.edit', $row->id).' " class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('stock.issue.events.delete', $row->id).' " class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })->rawColumns(['action'])->smart(true)->make(true);
        }

        return view('procurement.stock_issue.events.index');
    }

    public function create() {

        return view('procurement.stock_issue.events.ajax_view.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $event = new StockEvent();
        $event->name = $request->name;
        $event->description = $request->description;
        $event->save();

        return $event;
    }

    public function edit($id)
    {
        $event = DB::table('stock_events')->where('id', $id)->first();

        return view('procurement.stock_issue.events.ajax_view.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $updateEvents = StockEvent::find($id);
        $updateEvents->name = $request->name;
        $updateEvents->description = $request->description;
        $updateEvents->save();

        return response()->json('Stock issue event updated successfully');
    }

    public function delete(Request $request, $id)
    {

        $events = StockEvent::with(['stockIssues'])->where('id', $id)->first();

        if (count($events->stockIssues) > 0) {

            return response()->json(['errorMsg' => 'Can not delete the event. One or more stock issues are belonging in the event.']);
        }

        $events->delete();

        return response()->json('Stock issue event deleted successfully');
    }

    public function quickAddModalForm()
    {
        return view('procurement.stock_issue.events.ajax_view.add_quick_stock_event_modal');
    }
}
