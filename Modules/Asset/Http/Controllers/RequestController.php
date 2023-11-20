<?php

namespace Modules\Asset\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetRequest;
use Yajra\DataTables\Facades\DataTables;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('asset_requests_index')) {
            abort(403, 'Access denied.');
        }
        if ($request->ajax()) {

            $asset_request = '';

            $query = AssetRequest::with(
                [
                    'rel_to_asset:id,asset_name',
                    'rel_to_user:id,name',
                    'creator:id,name',
                ]
            );

            if ($request->f_asset_id) {
                $query->where('asset_id', $request->f_asset_id);
            }

            if ($request->f_request_for_id) {
                $query->where('request_for_id', $request->f_request_for_id);
            }

            if ($request->f_created_by_id) {
                $query->where('created_by_id', $request->f_created_by_id);
            }

            if ($request->f_date) {
                $from_date = date('Y-m-d', strtotime($request->f_date));
                $query->where('date', $from_date);
            }

            $asset_request = $query->get();

            return DataTables::of($asset_request)
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->can('asset_requests_update')) {
                        $html .= '<a class="dropdown-item" href="'.route('assets.request.edit', [$row->id]).'" id="edit_id"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->can('asset_requests_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('assets.request.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('asset', function ($row) {
                    return $row->rel_to_asset->asset_name ?? 'N/A';
                })
                ->editColumn('request_for', function ($row) {
                    return $row->rel_to_user->name ?? 'N/A';
                })
                ->editColumn('creator', function ($row) {
                    return $row->creator->name ?? 'N/A';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $assets = Asset::all();
        $users = User::all();

        return view('asset::request.index', [
            'assets' => $assets,
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {

        if (! auth()->user()->can('asset_requests_create')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'title' => 'required',
            'asset_id' => 'required',
            'request_for_id' => 'required',
            'date' => 'required',
        ]);

        $asset_request = new AssetRequest;
        $asset_request->title = $request->title;
        $asset_request->asset_id = $request->asset_id;
        $asset_request->request_for_id = $request->request_for_id;
        $asset_request->date = date('Y-m-d', strtotime($request->date));
        $asset_request->description = $request->description;
        $asset_request->created_by_id = auth()->user()->id;
        $asset_request->save();

        return response()->json('Request created successfully');
    }

    public function edit($id)
    {

        if (! auth()->user()->can('asset_requests_update')) {
            abort(403, 'Access denied.');
        }

        $asset_request = DB::table('asset_requests')->where('id', $id)->first();
        $assets = Asset::all();
        $users = User::all();

        return view('asset::request.ajax_view.edit', [
            'assets' => $assets,
            'users' => $users,
            'asset_request' => $asset_request,
        ]);
    }

    public function update(Request $request, $id)
    {

        if (! auth()->user()->can('asset_requests_update')) {
            abort(403, 'Access denied.');
        }

        $asset_request = AssetRequest::find($id);
        $asset_request->title = $request->title;
        $asset_request->asset_id = $request->asset_id;
        $asset_request->request_for_id = $request->request_for_id;
        $asset_request->date = date('Y-m-d', strtotime($request->date));
        $asset_request->description = $request->description;
        $asset_request->created_by_id = auth()->user()->id;

        $asset_request->save();

        return response()->json('Request updated successfully');
    }

    public function delete($id)
    {
        if (! auth()->user()->can('asset_requests_delete')) {
            abort(403, 'Access denied.');
        }
        $asset_request = AssetRequest::find($id);
        $asset_request->delete();

        return response()->json('Request deleted successfully');
    }
}
