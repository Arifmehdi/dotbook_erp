<?php

namespace App\Utils;

use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UnitUtil
{
    public function addUnit($request)
    {
        $addUnit = new Unit();
        $addUnit->name = $request->name;
        $addUnit->code_name = $request->short_name;
        $addUnit->base_unit_multiplier = $request->as_a_multiplier_of_other_unit == 1 ? $request->base_unit_multiplier : null;
        $addUnit->base_unit_id = $request->as_a_multiplier_of_other_unit == 1 ? $request->base_unit_id : null;
        $addUnit->created_by_id = auth()?->user()?->id;
        $addUnit->save();

        return $addUnit;
    }

    public function updateUnit($request, $id)
    {
        $updateUnit = Unit::where('id', $id)->first();
        $updateUnit->name = $request->name;
        $updateUnit->code_name = $request->short_name;
        $updateUnit->base_unit_multiplier = $request->as_a_multiplier_of_other_unit == 1 ? $request->base_unit_multiplier : null;
        $updateUnit->base_unit_id = $request->as_a_multiplier_of_other_unit == 1 ? $request->base_unit_id : null;
        $updateUnit->save();

        return $updateUnit;
    }

    public function unitList()
    {
        $units = DB::table('units')->leftJoin('units as baseUnit', 'units.base_unit_id', 'baseUnit.id')
            ->select(
                'units.id',
                'units.name',
                'units.code_name',
                'units.base_unit_multiplier',
                'baseUnit.name as base_unit_name',
                'baseUnit.code_name as base_unit_code_name',
            )->orderBy('name', 'asc');

        return DataTables::of($units)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown table-dropdown">';

                $html .= '<a href="'.route('products.units.edit', $row->id).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                //
                $html .= '<a href="'.route('products.units.delete', $row->id).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';

                $html .= '</div>';

                return $html;
            })->editColumn('name', function ($row) {

                $baseUnit = '';
                if ($row->base_unit_name) {

                    $baseUnit .= '(<strong>'.$row->base_unit_multiplier.' '.$row->base_unit_name.'</strong>)';
                }

                return $row->name.$baseUnit;
            })->editColumn('base_unit_name', function ($row) {

                if ($row->base_unit_name) {

                    return $row->base_unit_name.'('.$row->base_unit_code_name.')';
                }

            })->editColumn('multiplierUnitDetails', function ($row) {

                $multipleUnitDetails = '';
                if ($row->base_unit_name) {

                    $multipleUnitDetails .= __('menu.1').' '.$row->name.' = '.$row->base_unit_multiplier.' '.$row->base_unit_code_name;
                }

                return $multipleUnitDetails;
            })
            ->rawColumns(['action', 'name', 'base_unit_name', 'multipleUnitDetails'])
            ->smart(true)
            ->make(true);
    }
}
