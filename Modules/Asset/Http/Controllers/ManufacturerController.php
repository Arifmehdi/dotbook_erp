<?php

namespace Modules\Asset\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Asset\Entities\AssetLicenses;
use Modules\Asset\Entities\Manufacturer;
use Yajra\DataTables\Facades\DataTables;

class ManufacturerController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('asset_manufacturer_index')) {
            abort(403, 'Access denied.');
        }

        $manufacturers = Manufacturer::all();
        if ($request->ajax()) {
            return DataTables::of($manufacturers)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    if (auth()->user()->can('asset_manufacturer_update')) {
                        $html .= '<a href="'.route('assets.manufacturers.edit', $row->id).'" class="action-btn c-edit" id="edit_permission" title="Edit"><span class="fas fa-edit"></span></a>';
                    }
                    if (auth()->user()->can('asset_manufacturer_delete')) {
                        $html .= '<a href="'.route('assets.manufacturers.destroy', [$row->id]).'" class="action-btn c-delete" id="delete_permission" title="Delete"><span class="fas fa-trash "></span></a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('asset::manufacturers.index');
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('asset_manufacturer_create')) {
            abort(403, 'Access denied.');
        }

        $manufacturers = new Manufacturer;
        $manufacturers->name = $request->name;
        $manufacturers->save();

        return response()->json('Manufacturers created successfully');

    }

    public function edit($id)
    {
        if (! auth()->user()->can('asset_manufacturer_update')) {
            abort(403, 'Access denied.');
        }

        $manufacturer = DB::table('asset_manufacturers')->where('id', $id)->first();

        return view('asset::manufacturers.ajax_view.edit_modal_body', [
            'manufacturer' => $manufacturer,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('asset_manufacturer_update')) {
            abort(403, 'Access denied.');
        }

        $manufacturer = Manufacturer::where('id', $id)->first();
        $manufacturer->name = $request->name;
        $manufacturer->save();

        return response()->json('Manufacturers updated successfully');

    }

    public function destroy(Request $request, $id)
    {
        if (! auth()->user()->can('asset_manufacturer_delete')) {
            abort(403, 'Access denied.');
        }

        if (! auth()->user()->can('asset_index')) {
            abort(403, 'Access denied.');
        }

        $licenses = AssetLicenses::where('manufacturer_id', $id)->first();

        if (isset($licenses)) {
            return response()->json('Manufacturers is in use');
        }
        $manufacturer = Manufacturer::find($id);
        $manufacturer->delete();

        return response()->json('Manufacturers deleted successfully');
    }
}
