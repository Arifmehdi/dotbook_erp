<?php

namespace App\Http\Controllers;

use App\Models\CostCentreCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CostCentreCategoryController extends Controller
{
    public function create()
    {
        if (! auth()->user()->can('cost_centre_categories_add')) {
            abort(403, 'Access Forbidden.');
        }

        $categories = CostCentreCategory::with(['parentCategory:id,name', 'subCategories', 'subCategories.parentCategory:id,name'])->where('parent_category_id', null)->get();

        return view('finance.accounting.cost_centres.ajax_view.create_category', compact('categories'));
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('cost_centre_categories_add')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $addCostCentreCategory = new CostCentreCategory();
            $addCostCentreCategory->name = $request->name;
            $addCostCentreCategory->parent_category_id = $request->parent_category_id;
            $addCostCentreCategory->use_in_expense_items = $request->use_in_expense_items;
            $addCostCentreCategory->use_in_income_items = $request->use_in_income_items;
            $addCostCentreCategory->save();

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addCostCentreCategory;
    }

    public function edit($id)
    {
        if (! auth()->user()->can('cost_centre_categories_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $categories = CostCentreCategory::with(['parentCategory:id,name', 'subCategories', 'subCategories.parentCategory:id,name'])->where('parent_category_id', null)->get();
        $costCentreCategory = CostCentreCategory::where('id', $id)->first();

        return view('finance.accounting.cost_centres.ajax_view.edit_category', compact('categories', 'costCentreCategory'));
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('cost_centre_categories_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $updateCostCentreCategory = CostCentreCategory::where('id', $id)->first();
            $updateCostCentreCategory->name = $request->name;
            $updateCostCentreCategory->parent_category_id = $request->parent_category_id;
            $updateCostCentreCategory->use_in_expense_items = $request->use_in_expense_items;
            $updateCostCentreCategory->use_in_income_items = $request->use_in_income_items;
            $updateCostCentreCategory->save();

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Cost Centre Category Updated Successfully');
    }

    public function delete(Request $request, $id)
    {
        if (! auth()->user()->can('cost_centre_categories_delete')) {
            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $deleteCostCentreCategory = CostCentreCategory::with('subCategories', 'costCentres')->where('id', $id)->first();

            if (! is_null($deleteCostCentreCategory)) {

                if (count($deleteCostCentreCategory->subCategories) > 0 || count($deleteCostCentreCategory->costCentres) > 0) {

                    return response()->json(['errorMsg' => 'Cost Centre Category can not be deleted']);
                }

                $deleteCostCentreCategory->delete();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Cost Centre Category is deleted Successfully');
    }
}
