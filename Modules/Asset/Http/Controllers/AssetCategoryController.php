<?php

namespace Modules\Asset\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetCategory;
use Yajra\DataTables\Facades\DataTables;

class AssetCategoryController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('asset_categories_index')) {
            abort(403, 'Access denied.');
        }
        $asset_categories = AssetCategory::all();
        if ($request->ajax()) {
            return DataTables::of($asset_categories)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    if (auth()->user()->can('asset_categories_update')) {
                        $html .= '<a href="'.route('assets.categories.edit', $row->id).'" class="action-btn c-edit" id="edit_category" title="Edit"><span class="fas fa-edit"></span></a>';
                    }
                    if (auth()->user()->can('asset_categories_delete')) {
                        $html .= '<a href="'.route('assets.categories.destroy', [$row->id]).'" class="action-btn c-delete" id="delete_category" title="Delete"><span class="fas fa-trash "></span></a>';
                    }

                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('asset::categories.index', compact('asset_categories'));
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('asset_categories_create')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $assetCategory = new AssetCategory();
        $assetCategory->name = $request->name;
        $assetCategory->created_by = auth()->user()->id;
        $assetCategory->save();

        return response()->json('Asset category created successfully');
    }

    public function edit(Request $request)
    {
        if (! auth()->user()->can('asset_categories_update')) {
            abort(403, 'Access denied.');
        }

        $category = DB::table('asset_categories')->where('id', $request->id)->first();

        return view('asset::categories.ajax_view.edit_modal_body', compact('category'));
    }

    public function update(Request $request)
    {

        if (! auth()->user()->can('asset_categories_update')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $assetCategory = AssetCategory::find($request->id);
        $assetCategory->name = $request->name;
        $assetCategory->save();

        return response()->json('Category updated successfully');
    }

    public function destroy(Request $request)
    {

        if (! auth()->user()->can('asset_categories_delete')) {
            abort(403, 'Access denied.');
        }

        // Make a condition to stop deleting the use part
        $assets = Asset::all();

        foreach ($assets as $key => $asset) {
            if ($asset->asset_category_id == $request->id) {
                return response()->json(['errorMsg' => 'This category is assigned in an asset']);
            }
        }

        $assetCategory = AssetCategory::find($request->id);
        $assetCategory->delete();

        return response()->json('Category deleted successfully');
    }
}
