<?php

namespace Modules\Asset\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetLocation;
use Yajra\DataTables\Facades\DataTables;

class AssetLocationController extends Controller
{
    public function index(Request $request)
    {

        if (! auth()->user()->can('asset_locations_index')) {
            abort(403, 'Access denied.');
        }

        $asset_locations = AssetLocation::all();

        if ($request->ajax()) {

            return DataTables::of($asset_locations)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    if (auth()->user()->can('asset_locations_update')) {
                        $html .= '<a href="'.route('assets.locations.edit', $row->id).'" class="action-btn c-edit" id="edit_location" title="Edit"><span class="fas fa-edit"></span></a>';
                    }
                    if (auth()->user()->can('asset_locations_delete')) {
                        $html .= '<a href="'.route('assets.locations.destroy', [$row->id]).'" class="action-btn c-delete" id="delete_location" title="Delete"><span class="fas fa-trash "></span></a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('asset::locations.index', compact('asset_locations'));
    }

    public function store(Request $request)
    {

        if (! auth()->user()->can('asset_locations_create')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $assetLocation = new AssetLocation();
        $assetLocation->name = $request->name;
        $assetLocation->created_by = auth()->user()->id;
        $assetLocation->save();

        return response()->json('Asset location created successfully');
    }

    public function edit(Request $request)
    {
        if (! auth()->user()->can('asset_locations_update')) {
            abort(403, 'Access denied.');
        }

        $location = DB::table('asset_locations')->where('id', $request->id)->first();

        return view('asset::locations.ajax_view_loc.edit_modal_body', compact('location'));
    }

    public function update(Request $request)
    {
        if (! auth()->user()->can('asset_locations_update')) {
            abort(403, 'Access denied.');
        }
        $request->validate([
            'name' => 'required',
        ]);

        $assetLocation = AssetLocation::find($request->id);
        $assetLocation->name = $request->name;
        $assetLocation->save();

        return response()->json('Location updated successfully');
    }

    public function destroy(Request $request)
    {
        if (! auth()->user()->can('asset_locations_delete')) {
            abort(403, 'Access denied.');
        }

        // making a condition to stop deleting the used location
        $assets = Asset::all();

        foreach ($assets as $key => $asset) {
            if ($asset->asset_location_id == $request->id) {
                return response()->json('This location is assigned in an asset');
            }
        }

        $assetLocation = AssetLocation::find($request->id);
        $assetLocation->delete();

        return response()->json('Location deleted successfully');
    }
}
