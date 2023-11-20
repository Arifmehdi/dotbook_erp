<?php

namespace Modules\Asset\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Asset\Entities\AssetLicenses;
use Modules\Asset\Entities\LicensesCategory;
use Yajra\DataTables\Facades\DataTables;

class LicensesCategoryController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('asset_categories_index')) {
            abort(403, 'Access denied.');
        }
        $licensesCategory = LicensesCategory::all();
        if ($request->ajax()) {
            return DataTables::of($licensesCategory)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    if (auth()->user()->can('asset_categories_update')) {
                        $html .= '<a href="'.route('assets.licenses.category.edit', $row->id).'" class="action-btn c-edit" id="edit_licenses_category" title="Edit"><span class="fas fa-edit"></span></a>';
                    }
                    if (auth()->user()->can('asset_categories_delete')) {
                        $html .= '<a href="'.route('assets.licenses.category.destroy', [$row->id]).'" class="action-btn c-delete" id="delete_licenses_category" title="Delete"><span class="fas fa-trash "></span></a>';
                    }

                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('asset::licenses.category.index', compact('licensesCategory'));
    }

    public function store(Request $request)
    {

        if (! auth()->user()->can('asset_categories_create')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $licensesCategory = new LicensesCategory();
        $licensesCategory->name = $request->name;
        $licensesCategory->created_by = auth()->user()->id;
        $licensesCategory->save();

        return response()->json('Licenses category created successfully');
    }

    public function edit(Request $request, $id)
    {
        if (! auth()->user()->can('asset_categories_update')) {
            abort(403, 'Access denied.');
        }
        $licensesCategory = DB::table('licenses_categories')->where('id', $id)->first();

        return view('asset::licenses.category.ajax_view.edit_modal_body', compact('licensesCategory'));
    }

    public function update(Request $request, $id)
    {

        if (! auth()->user()->can('asset_categories_update')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $licensesCategory = LicensesCategory::find($id);
        $licensesCategory->name = $request->name;
        $licensesCategory->save();

        return response()->json('Licenses Category updated successfully');
    }

    public function destroy(Request $request)
    {

        if (! auth()->user()->can('asset_categories_delete')) {
            abort(403, 'Access denied.');
        }

        $asset_licenses = AssetLicenses::all();
        $licensesCategory = LicensesCategory::find($request->id);

        foreach ($asset_licenses as $key => $asset) {
            if ($asset->category_id == $request->id) {
                return response()->json(['errorMsg' => 'This category is assigned in a licenses']);
            }
        }

        $licensesCategory->delete();

        return response()->json('Licenses Category deleted successfully');
    }
}
