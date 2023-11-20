<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Utils\AccountLedgerUtil;
use App\Utils\CustomerOpeningBalanceUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerOpeningBalanceController extends Controller
{
    protected $customerOpeningBalanceUtil;

    protected $accountLedgerUtil;

    public function __construct(CustomerOpeningBalanceUtil $customerOpeningBalanceUtil, AccountLedgerUtil $accountLedgerUtil)
    {
        $this->customerOpeningBalanceUtil = $customerOpeningBalanceUtil;
        $this->accountLedgerUtil = $accountLedgerUtil;
    }

    public function update(Request $request)
    {
        try {

            DB::beginTransaction();

            $gs = DB::table('general_settings')->select('business')->first();
            $openingBalanceDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

            $account = Account::with('customer')->where('id', $request->customer_account_id)->first();
            $this->customerOpeningBalanceUtil->updateCustomerOpeningBalance($account->customer_id, $account->id, $request->opening_balance, $request->opening_balance_type, $request->user_id);

            $this->accountLedgerUtil->updateAccountLedger(
                voucher_type_id: 0,
                date: $openingBalanceDate,
                account_id: $account->id,
                trans_id: $account->id,
                amount: $request->opening_balance ? $request->opening_balance : 0,
                amount_type: $request->opening_balance_type,
                user_id: $request->user_id,
                current_account_id: $account->id,
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return 'success';
    }
}
