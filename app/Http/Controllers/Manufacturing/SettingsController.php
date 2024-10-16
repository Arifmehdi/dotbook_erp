<?php

namespace App\Http\Controllers\Manufacturing;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        if (! auth()->user()->can('manuf_settings')) {
            abort(403, 'Access Forbidden.');
        }

        return view('manufacturing.settings.index');
    }

    // Add tax settings
    public function store(Request $request)
    {
        if (! auth()->user()->can('manuf_settings')) {
            abort(403, 'Access Forbidden.');
        }

        $updateTaxSettings = GeneralSetting::first();
        $mfSettings = [
            'production_ref_prefix' => $request->production_ref_prefix,
            'enable_editing_ingredient_qty' => isset($request->enable_editing_ingredient_qty) ? 1 : 0,
            'enable_updating_product_price' => isset($request->enable_updating_product_price) ? 1 : 0,
        ];

        $updateTaxSettings->mf_settings = json_encode($mfSettings);
        $updateTaxSettings->save();

        return response()->json('Manufacturing settings updated successfully');
    }
}
