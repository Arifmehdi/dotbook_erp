<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleSettingsController extends Controller
{
    public function index()
    {
        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $priceGroups = DB::table('price_groups')->where('status', 'Active')->get();

        return view('sales_app.settings.index', compact('taxAccounts', 'priceGroups'));
    }

    public function saleSettingsUpdate(Request $request)
    {

        if (! auth()->user()->can('sale_settings')) {

            abort(403, 'Access Forbidden.');
        }

        $updateSaleSettings = GeneralSetting::first();
        $saleSettings = [
            'default_sale_discount_type' => $request->default_sale_discount_type,
            'default_sale_discount' => $request->default_sale_discount,
            'default_tax_id' => $request->default_tax_id,
            'default_price_group_id' => $request->default_price_group_id,
        ];

        $updateSaleSettings->sale = json_encode($saleSettings);
        $updateSaleSettings->save();

        return response()->json('Sale settings updated successfully');
    }

    public function posSettingsUpdate(Request $request)
    {
        $updatePosSettings = GeneralSetting::first();
        $posSettings = [
            'is_enabled_multiple_pay' => $request->is_enabled_multiple_pay,
            'is_enabled_draft' => $request->is_enabled_draft,
            'is_enabled_quotation' => $request->is_enabled_quotation,
            'is_enabled_suspend' => $request->is_enabled_suspend,
            'is_enabled_discount' => $request->is_enabled_discount,
            'is_enabled_order_tax' => $request->is_enabled_order_tax,
            'is_show_recent_transactions' => $request->is_show_recent_transactions,
            'is_enabled_credit_full_sale' => $request->is_enabled_credit_full_sale,
            'is_enabled_hold_invoice' => $request->is_enabled_hold_invoice,
        ];

        $updatePosSettings->pos = json_encode($posSettings);
        $updatePosSettings->save();

        return response()->json('POS settings updated successfully');
    }
}
