<?php

namespace App\Http\Controllers;

use App\Models\CostCentre;
use App\Models\CostCentreCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CostCentreController extends Controller
{
    public function index()
    {
        if (! auth()->user()->can('cost_centres_index')) {
            abort(403, 'Access Forbidden.');
        }

        return view('finance.accounting.cost_centres.index');
    }

    public function create()
    {
        if (! auth()->user()->can('cost_centres_add')) {
            abort(403, 'Access Forbidden.');
        }
        $categories = CostCentreCategory::with(['parentCategory:id,name', 'subCategories', 'subCategories.parentCategory:id,name'])->where('parent_category_id', null)->get();

        return view('finance.accounting.cost_centres.ajax_view.create_cost_centre', compact('categories'));
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('cost_centres_add')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $addCostCentre = new CostCentre();
            $addCostCentre->name = $request->name;
            $addCostCentre->category_id = $request->category_id;
            $addCostCentre->save();

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addCostCentre;
    }

    public function edit($id)
    {
        if (! auth()->user()->can('cost_centres_edit')) {
            abort(403, 'Access Forbidden.');
        }
        $categories = CostCentreCategory::with([
            'parentCategory:id,name',
            'subCategories',
            'subCategories.parentCategory:id,name',
        ])->where('parent_category_id', null)->get();
        $costCentre = CostCentre::where('id', $id)->first();

        return view('finance.accounting.cost_centres.ajax_view.edit_cost_centre', compact('categories', 'costCentre'));
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('cost_centres_edit')) {
            abort(403, 'Access Forbidden.');
        }
        $this->validate($request, [
            'name' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $updateCostCentre = CostCentre::where('id', $id)->first();
            $updateCostCentre->name = $request->name;
            $updateCostCentre->category_id = $request->category_id;
            $updateCostCentre->save();

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Cost Centre Updated Successfully');
    }

    public function delete(Request $request, $id)
    {
        if (! auth()->user()->can('cost_centres_delete')) {
            abort(403, 'Access Forbidden.');
        }
        try {

            DB::beginTransaction();

            $deleteCostCentre = CostCentre::with('voucherEntryCostCentres')->where('id', $id)->first();

            if (count($deleteCostCentre->voucherEntryCostCentres) > 0) {

                return response()->json(['errorMsg' => 'Cost Centre can not be deleted. This cost centre associated with vouchers']);
            }

            if (! is_null($deleteCostCentre)) {

                $deleteCostCentre->delete();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Cost Centre is deleted Successfully');
    }

    public function listOfCostCentres()
    {
        $categories = CostCentreCategory::with('costCentres', 'subCategories', 'subCategories.costCentres')->where('parent_category_id', null)->get();

        return view('finance.accounting.cost_centres.ajax_view.list_of_cost_centre', compact('categories'));
    }
}
