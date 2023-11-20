<?php

namespace Modules\CRM\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CRM\Entities\Settings;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $settings = Settings::find(1);

        return view('crm::settings.index', compact('settings'));
    }

    public function change(Request $request)
    {
        $settings = Settings::find(1);

        if (! isset($settings)) {
            $settings = new Settings();
        }

        $settings->is_enable = ($request->is_active) ? true : false;
        $settings->order_request_prefix = $request->prefix;
        $settings->save();

        return response()->json('CRM settings updated successfully');
    }
}
