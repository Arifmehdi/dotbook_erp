<?php

namespace Modules\Asset\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetUnit;
use Yajra\DataTables\Facades\DataTables;

class AssetUnitController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('asset_units_index')) {
            abort(403, 'Access denied.');
        }

        $asset_units = AssetUnit::all();
        if ($request->ajax()) {
            return DataTables::of($asset_units)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    if (auth()->user()->can('asset_units_update')) {
                        $html .= '<a href="'.route('assets.units.edit', $row->id).'" class="action-btn c-edit" id="edit_unit" title="Edit"><span class="fas fa-edit"></span></a>';
                    }
                    if (auth()->user()->can('asset_units_delete')) {
                        $html .= '<a href="'.route('assets.units.destroy', [$row->id]).'" class="action-btn c-delete" id="delete_unit" title="Delete"><span class="fas fa-trash "></span></a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('asset::units.index', compact('asset_units'));
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('asset_units_create')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $assetunits = new AssetUnit();
        $assetunits->name = $request->name;
        $assetunits->created_by = auth()->user()->id;
        $assetunits->save();

        return response()->json('Asset unit created successfully');
    }

    public function edit(Request $request)
    {
        if (! auth()->user()->can('asset_units_update')) {
            abort(403, 'Access denied.');
        }

        $units = DB::table('asset_units')->where('id', $request->id)->first();

        return view('asset::units.ajax_view_unit.edit_modal_body', compact('units'));
    }

    public function update(Request $request)
    {
        if (! auth()->user()->can('asset_units_update')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $assetunits = AssetUnit::find($request->id);
        $assetunits->name = $request->name;
        $assetunits->save();

        return response()->json('Unit updated successfully');
    }

    public function destroy(Request $request)
    {
        if (! auth()->user()->can('asset_units_delete')) {
            abort(403, 'Access denied.');
        }
        $assetunits = AssetUnit::find($request->id);
        // Making a counter to prevent deleting the use unit in asset
        $assets = Asset::all();
        foreach ($assets as $key => $asset) {
            if ($asset->asset_unit_id == $request->id) {
                return response()->json('This unit is assigned in an asset');

            }
        }

        $assetunits->delete();

        return response()->json('Unit deleted successfully');
    }
}
