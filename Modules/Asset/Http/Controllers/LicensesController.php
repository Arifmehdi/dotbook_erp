<?php

namespace Modules\Asset\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetLicenses;
use Modules\Asset\Entities\AssetSupplier;
use Modules\Asset\Entities\LicensesCategory;
use Modules\Asset\Entities\Manufacturer;
use Yajra\DataTables\Facades\DataTables;

class LicensesController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('asset_licenses_index')) {

            abort(403, 'Access denied.');
        }

        if ($request->ajax()) {

            $license = '';
            $query = AssetLicenses::with(
                [
                    'rel_to_licenses_category:id,name',
                ]
            );

            if ($request->f_category) {

                $query->where('category_id', $request->f_category);
            }

            if ($request->f_license_to_email) {

                $email = trim($request->f_license_to_email);
                $query->where('licensed_to_email', $request->f_license_to_email);
            }

            if ($request->f_supplier) {

                $query->where('supplier_id', $request->f_supplier);
            }

            if ($request->f_purchase_date) {
                $from_date = date('Y-m-d', strtotime($request->f_purchase_date));
                $query->where('purchase_date', $from_date);
            }

            if ($request->f_expire_date) {
                $expire_date = date('Y-m-d', strtotime($request->f_expire_date));
                $query->where('expire_date', $expire_date);
            }

            if ($request->f_termination_date) {
                $termination_date = date('Y-m-d', strtotime($request->f_termination_date));
                $query->where('termination_date', $termination_date);
            }

            $license = $query->get();

            return DataTables::of($license)
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->can('asset_licenses_update')) {
                        $html .= '<a class="dropdown-item" href="'.route('assets.licenses.edit', [$row->id]).'" id="edit_id"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->can('asset_licenses_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('assets.licenses.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('asset', function ($row) {

                    return $row->rel_to_asset->asset_name ?? 'N/A';
                })
                ->editColumn('category', function ($row) {

                    return $row->rel_to_licenses_category->name ?? 'N/A';
                })
                ->editColumn('supplier', function ($row) {

                    return $row->rel_to_supplier->name ?? 'N/A';
                })
                ->editColumn('re-assignable', function ($row) {

                    return ($row->re_assignable == 1) ? 'YES' : 'NO';
                })
                ->editColumn('maintained', function ($row) {

                    return ($row->maintained == 1) ? 'YES' : 'NO';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $licenses = AssetLicenses::all();
        $categories = LicensesCategory::all();
        $suppliers = AssetSupplier::all();
        $assets = Asset::all();
        $manufacturer = Manufacturer::all();

        return view('asset::licenses.index', [
            'assets' => $assets,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'licenses' => $licenses,
            'manufacturer' => $manufacturer,
        ]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('asset_licenses_create')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'asset_name' => 'required',
            'categories_id' => 'required',
            'licensed_to_name' => 'required',
            'licensed_to_email' => 'required',
            'supplier' => 'required',
            'purchase_cost' => 'required',
            'purchase_date' => 'required',
            'expire_date' => 'required',
            'manufacturer' => 'required',
        ]);

        $purchase_date = date('Y-m-d', strtotime($request->purchase_date));
        $expire_date = date('Y-m-d', strtotime($request->expire_date));

        if ($purchase_date > $expire_date) {
            return response()->json(['errorMsg' => 'Invalid date']);
        }

        $licenses = new AssetLicenses();
        $licenses->asset_id = $request->asset_name;
        $licenses->category_id = $request->categories_id;
        $licenses->seats = $request->seats;
        $licenses->manufacturer_id = $request->manufacturer;
        $licenses->licensed_to_name = $request->licensed_to_name;
        $licenses->licensed_to_email = $request->licensed_to_email;
        $licenses->supplier_id = $request->supplier;
        $licenses->product_key = $request->product_key;
        $licenses->order_number = $request->order_number;
        $licenses->purchase_order_number = $request->purchase_order_number;
        $licenses->purchase_cost = $request->purchase_cost;
        $licenses->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
        $licenses->expire_date = date('Y-m-d', strtotime($request->expire_date));
        if (isset($request->termination_date)) {
            $licenses->termination_date = date('Y-m-d', strtotime($request->termination_date));
        }
        $licenses->depreciation_id = $request->depreciation;
        $licenses->re_assignable = isset($request->re_assignable) ? 1 : 0;
        $licenses->maintained = isset($request->maintained) ? 1 : 0;
        $licenses->created_by_id = auth()->user()->id;
        $licenses->description = $request->description;
        $licenses->save();

        return response()->json('Licenses created successfully');
    }

    public function edit($id)
    {
        if (! auth()->user()->can('asset_licenses_update')) {

            abort(403, 'Access denied.');
        }

        $categories = DB::table('licenses_categories')->get();
        $suppliers = DB::table('suppliers')->get();
        $assets = DB::table('assets')->get();
        $licenses = DB::table('asset_licenses')->where('id', $id)->first();
        $manufacturer = Manufacturer::all();

        return view('asset::licenses.ajax_view.edit', [
            'categories' => $categories,
            'suppliers' => $suppliers,
            'assets' => $assets,
            'licenses' => $licenses,
            'manufacturer' => $manufacturer,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('asset_licenses_update')) {

            abort(403, 'Access denied.');
        }

        $request->validate([
            'asset_name' => 'required',
            'categories_id' => 'required',
            'licensed_to_name' => 'required',
            'licensed_to_email' => 'required',
            'supplier' => 'required',
            'purchase_cost' => 'required',
            'purchase_date' => 'required',
            'expire_date' => 'required',
            'manufacturer' => 'required',
        ]);

        $purchase_date = date('Y-m-d', strtotime($request->purchase_date));
        $expire_date = date('Y-m-d', strtotime($request->expire_date));

        if ($purchase_date > $expire_date) {
            return response()->json(['errorMsg' => 'Invalid date']);
        }

        $licenses = AssetLicenses::where('id', $id)->first();
        $licenses->asset_id = $request->asset_name;
        $licenses->category_id = $request->categories_id;
        $licenses->seats = $request->seats;
        $licenses->manufacturer_id = $request->manufacturer;
        $licenses->licensed_to_name = $request->licensed_to_name;
        $licenses->licensed_to_email = $request->licensed_to_email;
        $licenses->supplier_id = $request->supplier;
        $licenses->order_number = $request->order_number;
        $licenses->product_key = $request->product_key;
        $licenses->purchase_order_number = $request->purchase_order_number;
        $licenses->purchase_cost = $request->purchase_cost;
        $licenses->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
        $licenses->expire_date = date('Y-m-d', strtotime($request->expire_date));

        if (isset($request->termination_date)) {
            $licenses->termination_date = date('Y-m-d', strtotime($request->termination_date));
        }
        $licenses->depreciation_id = $request->depreciation;
        $licenses->re_assignable = isset($request->re_assignable) ? 1 : 0;
        $licenses->maintained = isset($request->maintained) ? 1 : 0;
        $licenses->description = $request->description;
        $licenses->save();

        return response()->json('Licenses updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        if (! auth()->user()->can('asset_licenses_delete')) {

            abort(403, 'Access denied.');
        }

        $licenses = AssetLicenses::find($request->id);
        $licenses->delete();

        return response()->json('Licenses deleted successfully');
    }
}
