<?php

namespace Modules\Asset\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Asset\Entities\Components;
use Yajra\DataTables\Facades\DataTables;

class ComponentsController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('asset_components_index')) {
            abort(403, 'Access denied.');
        }

        $asset_components = Components::all();
        if ($request->ajax()) {
            return DataTables::of($asset_components)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    if (auth()->user()->can('asset_components_update')) {

                        $html .= '<a href="'.route('assets.components.edit', $row->id).'" class="action-btn c-edit" id="edit_components" title="Edit"><span class="fas fa-edit"></span></a>';
                    }
                    if (auth()->user()->can('asset_components_delete')) {

                        $html .= '<a href="'.route('assets.components.destroy', [$row->id]).'" class="action-btn c-delete" id="delete_components" title="Delete"><span class="fas fa-trash "></span></a>';
                    }
                    $html .= '</div>';

                    return $html;
                })

                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('asset::components.index', [
            'asset_components' => $asset_components,
        ]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('asset_components_create')) {

            abort(403, 'Access denied.');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $components = new Components();
        $components->name = $request->name;
        $components->save();

        return response()->json('Asset components created successfully');
    }

    public function edit($id)
    {
        if (! auth()->user()->can('asset_components_update')) {

            abort(403, 'Access denied.');
        }

        $components = DB::table('components')->where('id', $id)->first();

        return view('asset::components.ajax_view.edit_modal_body', compact('components'));
    }

    public function update(Request $request)
    {
        if (! auth()->user()->can('asset_components_update')) {

            abort(403, 'Access denied.');
        }

        $request->validate([
            'e_name' => 'required',
        ]);

        $components = Components::find($request->id);
        $components->name = $request->e_name;
        $components->save();

        return response()->json('Components updated successfully');
    }

    public function destroy(Request $request)
    {
        if (! auth()->user()->can('asset_components_delete')) {
            abort(403, 'Access denied.');
        }

        $components = Components::find($request->id);

        if ($components->checker > 0) {
            return response()->json('Components is in use');
        }
        $components->delete();

        return response()->json('Components deleted successfully');
    }
}
