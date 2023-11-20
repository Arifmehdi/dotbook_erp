<?php

namespace App\Http\Controllers\Requisitions;

use App\Http\Controllers\Controller;
use App\Models\Requester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RequestersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $requesters = DB::table('requesters')->orderBy('requesters.name', 'asc')->get();

            return DataTables::of($requesters)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="'.route('requesters.edit', [$row->id]).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('requesters.destroy', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })->rawColumns(['action'])->smart(true)->make(true);
        }

        return view('procurement.requisitions.requesters.index');
    }

    public function create()
    {
        return view('procurement.requisitions.requesters.ajax_view.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $requesters = new Requester();
        $requesters->name = $request->name;
        $requesters->phone_number = $request->phone;
        $requesters->area = $request->address;
        $requesters->save();

        return $requesters;
    }

    public function edit($id)
    {
        $requesters = Requester::find($id);

        return view('procurement.requisitions.requesters.ajax_view.edit', [
            'requesters' => $requesters,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $requester = Requester::find($id);
        $requester->name = $request->name;
        $requester->phone_number = $request->phone;
        $requester->area = $request->address;
        $requester->save();

        return response()->json('Requester updated successfully!');
    }

    public function destroy($id)
    {
        $requester = Requester::with(['requisitions'])->where('id', $id)->first();

        if (count($requester->requisitions) > 0) {

            return response()->json(['errorMsg' => 'Can not delete the requester. One or more requisitions are belonging in the requester.']);
        }

        $requester->delete();

        return response()->json('Requester deleted successfully!');
    }
}
