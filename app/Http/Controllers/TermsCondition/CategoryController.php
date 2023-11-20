<?php

namespace App\Http\Controllers\TermsCondition;

use App\Http\Controllers\Controller;
use App\Models\TermsCondition\TermsConditionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $categories = TermsConditionCategory::all();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="javascript:;" class="action-btn c-edit" id="edit_category" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('terms.category.delete', [$row->id]).'" class="action-btn c-delete" id="delete_category" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('terms.category.edit', $row->id);
                    },
                ])
                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('terms_and_condition.categories.bodyPartial.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $category = new TermsConditionCategory;

        $category->category = $request->name;
        $category->created_by = auth()->user()->id;
        $category->save();

        return response()->json('Category Added');
    }

    public function edit(Request $request)
    {

        $category = DB::table('terms_condition_categories')->where('id', $request->id)->first();

        return view('terms_and_condition.categories.ajax_view.edit_modal_body', compact('category'));
    }

    public function update(Request $request)
    {
        // print_r($request->all());

        $category = DB::table('terms_condition_categories')->where('id', $request->id)->limit(1)->update(['category' => $request->name]);

        return response()->json('Category Updated');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        DB::table('terms_condition_categories')->where('id', $id)->delete();

        return response()->json('Category Deleted');
    }
}
