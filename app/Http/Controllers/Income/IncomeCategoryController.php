<?php

namespace App\Http\Controllers\Income;

use App\Http\Controllers\Controller;
use App\Models\IncomeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class IncomeCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $income = DB::table('income_categories')->orderBy('id', 'DESC')->get();

            return DataTables::of($income)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="'.route('income.categories.edit', [$row->id]).'" class="action-btn edit c-edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('income.categories.delete', [$row->id]).'" class="action-btn c-delete " id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('income.categories.index');
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|unique:income_categories,name',
        ]);

        $lastIncomeCategory = DB::table('income_categories')->orderBy('id', 'desc')->first();
        $code = 0;
        if ($lastIncomeCategory) {
            $code = ++$lastIncomeCategory->id;
        } else {
            $code = 1;
        }
        IncomeCategory::insert([
            'name' => $request->name,
            'code' => $request->code ? $request->code : $code,
            'created_by_id' => auth()->user()->id,
        ]);

        return response()->json('Income category created successfully!');
    }

    public function edit($id)
    {
        $income = IncomeCategory::find($id);

        return view('income.categories.ajax_view.edit', compact('income'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required|unique:income_categories,name,'.$id,
        ]);

        $income = IncomeCategory::find($id);
        $income->name = $request->name;
        $income->save();

        return response()->json('Income category updated successfully!');
    }

    public function delete($id)
    {
        $income = IncomeCategory::find($id);
        $income->delete();

        return response()->json('Income category deleted successfully!');
    }
}
