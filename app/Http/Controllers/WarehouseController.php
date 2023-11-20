<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WarehouseController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('warehouse')) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $warehouses = DB::table('warehouses')->select(
                'warehouses.id',
                'warehouses.warehouse_name as name',
                'warehouses.phone',
                'warehouses.address',
                'warehouses.warehouse_code as code',
            )->orderBy('warehouses.id', 'desc');

            return DataTables::of($warehouses)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="'.route('settings.warehouses.edit', [$row->id]).'" class="action-btn c-edit edit" id="edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('settings.warehouses.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.warehouses.index');
    }

    public function store(Request $request)
    {
        //return count($request->branch_ids);
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
        ]);

        $addons = DB::table('addons')->select('id', 'branches')->first();

        $addWarehouse = new Warehouse();
        $addWarehouse->warehouse_name = $request->name;
        $addWarehouse->warehouse_code = $request->code;
        $addWarehouse->phone = $request->phone;
        $addWarehouse->address = $request->address;
        $addWarehouse->save();

        return response()->json('Successfully warehouse is added');
    }

    public function edit($id)
    {
        $w = Warehouse::where('id', $id)->first();

        return view('settings.warehouses.ajax_view.edit', compact('w'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
        ]);

        $updateWarehouse = Warehouse::where('id', $id)->first();
        $updateWarehouse->warehouse_name = $request->name;
        $updateWarehouse->warehouse_code = $request->code;
        $updateWarehouse->phone = $request->phone;
        $updateWarehouse->address = $request->address;
        $updateWarehouse->save();

        return response()->json('Successfully warehouse is updated');
    }

    public function delete(Request $request, $warehouseId)
    {
        $deleteWarehouse = Warehouse::where('id', $warehouseId)->first();
        if (! is_null($deleteWarehouse)) {

            $deleteWarehouse->delete();
        }

        return response()->json('Successfully warehouse is deleted');
    }
}
