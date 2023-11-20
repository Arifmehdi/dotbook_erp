<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethodSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentMethodSettingsController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $accounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('account_groups.sub_sub_group_number', [1, 2, 11])
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        return view('settings.payment_settings.index', compact('accounts', 'methods'));
    }

    public function update(Request $request)
    {
        if (isset($request->method_ids)) {

            $index = 0;
            foreach ($request->method_ids as $method_id) {

                $updateSetting = PaymentMethodSetting::where('payment_method_id', $method_id)->first();

                if (! $updateSetting) {

                    $add = new PaymentMethodSetting();
                    $add->payment_method_id = $method_id;
                    $add->account_id = $request->account_ids[$index];
                    $add->save();
                } else {

                    $updateSetting->payment_method_id = $method_id;
                    $updateSetting->account_id = $request->account_ids[$index];
                    $updateSetting->save();
                }

                $index++;
            }
        } else {

            return response()->json(['errorMsg' => 'Failed! Payment method is empty.']);
        }

        return response()->json('Successfully Payment method settings is updated.');
    }
}
