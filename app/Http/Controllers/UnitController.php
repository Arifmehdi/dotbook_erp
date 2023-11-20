<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Utils\UnitUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    protected $unitUtil;

    protected $userActivityLogUtil;

    public function __construct(UnitUtil $unitUtil, UserActivityLogUtil $userActivityLogUtil)
    {
        $this->unitUtil = $unitUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('units')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->unitUtil->unitList();
        }

        return view('inventories.units.index');
    }

    public function create($isAllowedMultipleUnit = 0)
    {
        $baseUnits = DB::table('units')->select('id', 'name', 'code_name')->where('base_unit_id', null)->orderBy('name', 'asc')->get();

        return view('inventories.units.ajax_view.create_modal_view', compact('baseUnits', 'isAllowedMultipleUnit'));
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('units')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'short_name' => 'required',
        ]);

        if ($request->as_a_multiplier_of_other_unit == 1) {

            $this->validate($request, [
                'base_unit_multiplier' => 'required|numeric',
                'base_unit_id' => 'required',
            ], [
                'base_unit_multiplier.required' => 'Amount field is required',
                'base_unit_id.required' => 'Base unit field is required',
            ]);
        }

        $addUnit = $this->unitUtil->addUnit($request);

        if ($addUnit) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 23, data_obj: $addUnit);
        }

        return $addUnit;
    }

    public function edit($id)
    {
        $unit = DB::table('units')->where('id', $id)->first();
        $baseUnits = DB::table('units')->select('id', 'name', 'code_name')->where('base_unit_id', null)->orderBy('name', 'asc')->get();

        return view('inventories.units.ajax_view.edit_modal_view', compact('unit', 'baseUnits'));
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('units')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'short_name' => 'required',
        ]);

        if ($request->as_a_multiplier_of_other_unit == 1) {

            $this->validate($request, [
                'base_unit_multiplier' => 'required|numeric',
                'base_unit_id' => 'required',
            ], [
                'base_unit_multiplier.required' => 'Amount field is required',
                'base_unit_id.required' => 'Base unit field is required',
            ]);
        }

        $updateUnit = $this->unitUtil->updateUnit($request, $id);

        if ($updateUnit) {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 23, data_obj: $updateUnit);
        }

        return response()->json('Successfully unit is updated');
    }

    public function delete($id)
    {
        if (! auth()->user()->can('units')) {

            abort(403, 'Access Forbidden.');
        }

        $deleteUnit = Unit::find($id);

        if (count($deleteUnit->products) > 0) {

            return response()->json(['errorMsg' => 'Unit can not be deleted. One or more products is belonging under this unit.']);
        }

        if (! is_null($deleteUnit)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 23, data_obj: $deleteUnit);

            $deleteUnit->delete();
        }

        return response()->json('Successfully unit is deleted');
    }
}
