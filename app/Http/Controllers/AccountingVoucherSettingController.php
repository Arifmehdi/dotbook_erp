<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class AccountingVoucherSettingController extends Controller
{
    public function index()
    {
        return view('finance.settings.voucher_settings.index');
    }

    public function update(Request $request)
    {
        if (! auth()->user()->can('sale_settings')) {

            abort(403, 'Access Forbidden.');
        }

        $updateAccountingVouchersSettings = GeneralSetting::first();
        $accountVouchersSettings = [
            'add_transaction_details' => $request->add_transaction_details,
            'maintain_cost_centre' => $request->maintain_cost_centre,
            'show_cost_centre_list' => $request->show_cost_centre_list,
            'all_voucher_maintain_by_approval' => $request->all_voucher_maintain_by_approval,
        ];

        $updateAccountingVouchersSettings->accounting_vouchers = json_encode($accountVouchersSettings);
        $updateAccountingVouchersSettings->save();

        return response()->json('Voucher Settings is updated successfully');
    }
}
