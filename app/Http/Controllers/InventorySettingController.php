<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventorySettingController extends Controller
{
    public function index()
    {
        $units = DB::table('units')->select('id', 'name', 'code_name')->get();

        return view('inventories.settings.index', compact('units'));
    }

    public function itemSettingsUpdate(Request $request)
    {
        if (! auth()->user()->can('product_settings')) {

            abort(403, 'Access Forbidden.');
        }

        $updateProductSettings = GeneralSetting::first();

        $productSettings = [
            'product_code_prefix' => $request->product_code_prefix,
            'default_unit_id' => $request->default_unit_id,
            'is_enable_brands' => $request->is_enable_brands,
            'is_enable_categories' => $request->is_enable_categories,
            'is_enable_sub_categories' => $request->is_enable_sub_categories,
            'is_enable_price_tax' => $request->is_enable_price_tax,
            'is_enable_warranty' => $request->is_enable_warranty,
        ];

        $updateProductSettings->product = json_encode($productSettings);
        $updateProductSettings->save();

        return response()->json('item settings updated successfully');
    }
}
