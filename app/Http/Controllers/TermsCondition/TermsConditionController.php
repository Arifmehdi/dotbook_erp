<?php

namespace App\Http\Controllers\TermsCondition;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\TermsCondition\TermsCondition;
use App\Models\TermsCondition\TermsConditionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TermsConditionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $terms_condition = '';
            $query = TermsCondition::with(
                [
                    'category:id,category',
                    'creator:id,prefix,name,last_name',
                    'updater:id,prefix,name,last_name',
                ]
            );

            if ($request->category_id) {

                $query->where('category_id', $request->category_id);
            }

            $terms_condition = $query->get();

            return DataTables::of($terms_condition)
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="'.route('terms.edit', [$row->id]).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete_terms_condition" href="'.route('terms.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('category', function ($row) {
                    return $row->category->category ?? 'N/A';
                })
                ->editColumn('creator', function ($row) {
                    return $row->creator->name ?? 'N/A';
                })
                ->editColumn('updater', function ($row) {
                    return $row->updater->name ?? 'N/A';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $suppliers = Supplier::all();
        $customers = Customer::all();
        $categories = TermsConditionCategory::all();

        return view('terms_and_condition.index', [
            'categories' => $categories,
            'suppliers' => $suppliers,
            'customers' => $customers,
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'categories_id' => 'required',
        ]);

        $terms_condition = new TermsCondition;
        $terms_condition->title = $request->title;
        $terms_condition->category_id = $request->categories_id;
        $terms_condition->description = $request->description;
        $terms_condition->created_by_id = auth()->user()->id;
        $terms_condition->save();

        return response()->json('Terms Condition Saved');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        DB::table('terms_and_conditions')->where('id', $id)->delete();

        return response()->json('Category Deleted');
    }

    public function edit(Request $request)
    {
        $terms_condition = DB::table('terms_and_conditions')->where('id', $request->id)->first();
        $suppliers = Supplier::all();
        $customers = Customer::all();
        $categories = TermsConditionCategory::all();

        return view('terms_and_condition.terms_condition.ajax_view_loc.edit_modal_body', [
            'terms_condition' => $terms_condition,
            'suppliers' => $suppliers,
            'customers' => $customers,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
        ]);
        $terms_condition = TermsCondition::where('id', $id)->first();
        $terms_condition->title = $request->title;
        $terms_condition->category_id = $request->category_id;
        $terms_condition->description = $request->description;
        $terms_condition->updated_by_id = auth()->user()->id;
        $terms_condition->save();

        return response()->json('Terms Condition Updated Successfully');
    }
}
