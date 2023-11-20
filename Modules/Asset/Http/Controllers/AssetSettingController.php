<?php

namespace Modules\Asset\Http\Controllers;

class AssetSettingController extends Controller
{
    public function index()
    {
        if (! auth()->user()->can('asset_settings')) {
            abort(403, 'Access denied.');
        }

        return view('asset::setting');
    }
}
