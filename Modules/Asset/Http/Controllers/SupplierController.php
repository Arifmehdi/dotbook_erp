<?php

namespace Modules\Asset\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Asset\Entities\AssetSupplier;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $asset_supplier = AssetSupplier::all();
            $generalSettings = DB::table('general_settings')->first();

            return DataTables::of($asset_supplier)

                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->can('asset_allocation_update')) {
                        $html .= '<a class="dropdown-item" href="'.route('assets.supplier.edit', [$row->id]).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->can('asset_allocation_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('assets.supplier.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('status', function ($row) {
                    return ($row->status == 1) ? 'YES' : 'NO';
                })
                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('asset::supplier.index');
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {

        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
        ]);

        $asset_supplier = new AssetSupplier();
        $asset_supplier->supplier_code = $request->asset_code ? $request->asset_code : $codeGenerationService->generate('assets_suppliers', 'supplier_code', 'ASU');
        $asset_supplier->name = $request->name;
        $asset_supplier->phone = $request->phone;
        $asset_supplier->status = isset($request->status) ? 1 : 0;
        $asset_supplier->alternative_phone = $request->alternative_phone;
        $asset_supplier->email = $request->email;
        $asset_supplier->address = $request->address;
        $asset_supplier->save();

        return response()->json('Supplier created successfully');
    }

    public function edit($id)
    {

        if (! auth()->user()->can('asset_allocation_update')) {
            abort(403, 'Access denied.');
        }

        $asset_supplier = AssetSupplier::where('id', $id)->first();

        return view('asset::supplier.ajax_view.edit', [
            'asset_supplier' => $asset_supplier,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('asset_allocation_update')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
        ]);
        $asset_supplier = AssetSupplier::where('id', $id)->first();

        $asset_supplier->name = $request->name;
        $asset_supplier->phone = $request->phone;
        $asset_supplier->status = isset($request->status) ? 1 : 0;
        $asset_supplier->alternative_phone = $request->alternative_phone;
        $asset_supplier->email = $request->email;
        $asset_supplier->address = $request->address;

        $asset_supplier->save();

        return response()->json('Supplier updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        if (! auth()->user()->can('asset_allocation_delete')) {

            abort(403, 'Access denied.');
        }

        $asset_supplier = AssetSupplier::where('id', $id)->first();

        $asset_supplier->delete();

        return response()->json(['errorMsg' => 'Supplier deleted successfully']);
    }
}
