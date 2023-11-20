<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use App\Utils\AccountLedgerUtil;
use App\Utils\AccountUtil;
use App\Utils\Converter;
use App\Utils\CustomerOpeningBalanceUtil;
use App\Utils\CustomerUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\SupplierUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function __construct(
        private AccountUtil $accountUtil,
        private Converter $converter,
        private UserActivityLogUtil $userActivityLogUtil,
        private CustomerUtil $customerUtil,
        private SupplierUtil $supplierUtil,
        private AccountLedgerUtil $accountLedgerUtil,
        private CustomerOpeningBalanceUtil $customerOpeningBalanceUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('accounts_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->accountUtil->accountListTable($request);
        }

        $banks = DB::table('banks')->get();
        $groups = DB::table('account_groups')->where('is_main_group', 0)->get();

        return view('finance.accounting.accounts.index', compact('banks', 'groups'));
    }

    public function voucherList(Request $request, $id, $by)
    {
        if ($request->ajax()) {

            return $this->accountLedgerUtil->accountVoucherList($request, $id, $by);
        }
    }

    public function accountCreateModal()
    {
        if (! auth()->user()->can('accounts_add')) {

            abort(403, 'Access Forbidden.');
        }

        $groups = AccountGroup::with('parentGroup')->where('is_main_group', 0)->get();
        $banks = DB::table('banks')->select('id', 'name')->get();
        $srUsers = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name')->get();

        return view('finance.accounting.accounts.ajax_view.create_account_modal', compact('groups', 'banks', 'srUsers'));
    }

    public function store(Request $request, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        if (! auth()->user()->can('accounts_add')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'account_group_id' => 'required',
        ]);

        $accountGroup = AccountGroup::where('id', $request->account_group_id)->first();

        try {

            DB::beginTransaction();

            $gs = DB::table('general_settings')->select('business', 'prefix')->first();
            $openingBalanceDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));
            $user_id = $request->user_id ?? auth()->user()->id;

            $addAccount = $this->accountUtil->addAccount($request);

            if ($accountGroup->sub_sub_group_number == 6) {

                $addAccount->phone = $request->customer_phone_no;
                $addAccount->address = $request->customer_address;

                $request->phone = $request->customer_phone_no;
                $request->address = $request->customer_address;
                $request->customer_type = $request->customer_type;
                $request->credit_limit = $request->customer_credit_limit;
                $request->pay_term = 1;

                $addCustomer = $this->customerUtil->addCustomer($request, $invoiceVoucherRefIdUtil, $gs);

                if (isset($request->sr_user_ids)) {

                    $index = 0;
                    foreach ($request->sr_user_ids as $sr_user_id) {

                        if ($sr_user_id) {

                            $this->customerOpeningBalanceUtil->addCustomerOpeningBalance(customer_id: $addCustomer->id, account_id: $addAccount->id, opening_balance: $request->sr_opening_balances[$index], opening_balance_type: $request->sr_opening_balance_types[$index], user_id: $sr_user_id);

                            $this->accountLedgerUtil->addAccountLedger(
                                voucher_type_id: 0,
                                date: $openingBalanceDate,
                                account_id: $addAccount->id,
                                trans_id: $addAccount->id,
                                amount: $request->sr_opening_balances[$index] ? $request->sr_opening_balances[$index] : 0,
                                amount_type: $request->sr_opening_balance_types[$index],
                                user_id: $sr_user_id,
                            );
                        }
                        $index++;
                    }
                }

                $addAccount->customer_id = $addCustomer->id;
                $addAccount->save();
            }

            if ($accountGroup->sub_sub_group_number == 10) {

                $request->phone = $request->supplier_phone_no;
                $request->address = $request->supplier_address;

                $addAccount->phone = $request->supplier_phone_no;
                $addAccount->address = $request->supplier_address;

                $addSupplier = $this->supplierUtil->addSupplier($request, $invoiceVoucherRefIdUtil, $gs);
                $addAccount->supplier_id = $addSupplier->id;
                $addAccount->save();
            }

            // Opening Balance ledger
            if ($accountGroup->sub_sub_group_number != 6) {

                $this->accountLedgerUtil->addAccountLedger(
                    voucher_type_id: 0,
                    date: $openingBalanceDate,
                    account_id: $addAccount->id,
                    trans_id: $addAccount->id,
                    amount: $request->opening_balance ? $request->opening_balance : 0,
                    amount_type: $request->opening_balance_type,
                );
            }

            $account = DB::table('accounts')
                ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                ->where('accounts.id', $addAccount->id)
                ->select('accounts.*', 'account_groups.name as group')->first();

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 17, data_obj: $account);

            $amounts = $this->accountUtil->accountClosingBalance($account->id);

            // $balance = $this->converter->format_in_bdt($amounts['closing_balance']) . ' ' . $amounts['closing_balance_side_st_name'];

            $account->balance = $amounts['closing_balance_string'];

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $account;
    }

    public function edit($id)
    {
        if (! auth()->user()->can('accounts_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $account = Account::with(
            [
                'group',
                'customer:id,name,phone,address,customer_type,credit_limit,pay_term,pay_term_number',
                'customer.openingBalances',
                'customer.openingBalances.user:id,prefix,name,last_name',
            ]
        )->where('id', $id)->first();

        $groups = AccountGroup::with('parentGroup')->where('is_main_group', 0)->get();
        $banks = DB::table('banks')->get();
        $srUsers = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name')->get();

        return view('finance.accounting.accounts.ajax_view.edit_account_modal', compact('account', 'groups', 'banks', 'srUsers'));
    }

    public function update($id, Request $request, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        if (! auth()->user()->can('accounts_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'account_group_id' => 'required',
        ]);

        $accountGroup = AccountGroup::where('id', $request->account_group_id)->first();

        try {

            DB::beginTransaction();

            $gs = DB::table('general_settings')->select('business')->first();
            $openingBalanceDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

            $account = Account::with('group', 'customer')->where('id', $id)->first();
            $accountGroup = AccountGroup::where('id', $request->account_group_id)->first();

            if ($account->group->sub_sub_group_number == 6 && $accountGroup->sub_sub_group_number != 6) {

                return response()->json(['errorMsg' => 'Account group can not be changed from current group to another group. This account is associated with customer.']);
            }

            if ($account->group->sub_sub_group_number == 10 && $accountGroup->sub_sub_group_number != 10) {

                return response()->json(['errorMsg' => 'Account group can not be changed from current group to another group. This account is associated with supplier.']);
            }

            $updateAccount = $this->accountUtil->updateAccount($request, $account);

            if ($accountGroup->sub_sub_group_number == 6) {

                $updateAccount->phone = $request->customer_phone_no;
                $updateAccount->address = $request->customer_address;

                $request->phone = $request->customer_phone_no;
                $request->address = $request->customer_address;
                $request->customer_type = $request->customer_type;
                $request->credit_limit = $request->customer_credit_limit;
                $request->pay_term = 1;

                if ($account->customer) {

                    $updateCustomer = $this->customerUtil->updateCustomer($request, $account->customer);

                    if (isset($request->sr_user_ids)) {

                        $index = 0;
                        foreach ($request->sr_user_ids as $sr_user_id) {

                            if ($sr_user_id) {

                                $this->customerOpeningBalanceUtil->updateCustomerOpeningBalance(customer_id: $updateCustomer->id, account_id: $updateAccount->id, opening_balance: $request->sr_opening_balances[$index], opening_balance_type: $request->sr_opening_balance_types[$index], user_id: $sr_user_id);

                                $this->accountLedgerUtil->updateAccountLedger(
                                    voucher_type_id: 0,
                                    date: $openingBalanceDate,
                                    account_id: $updateAccount->id,
                                    trans_id: $updateAccount->id,
                                    amount: $request->sr_opening_balances[$index] ? $request->sr_opening_balances[$index] : 0,
                                    amount_type: $request->sr_opening_balance_types[$index],
                                    user_id: $sr_user_id,
                                );
                            }
                            $index++;
                        }
                    }
                } else {

                    $addCustomer = $this->customerUtil->addCustomer($request, $invoiceVoucherRefIdUtil, $gs);

                    if (isset($request->sr_user_ids)) {

                        $index = 0;
                        foreach ($request->sr_user_ids as $sr_user_id) {

                            if ($sr_user_id) {

                                $this->customerOpeningBalanceUtil->addCustomerOpeningBalance(customer_id: $addCustomer->id, account_id: $addAccount->id, opening_balance: $request->sr_opening_balances[$index], opening_balance_type: $request->sr_opening_balance_types[$index], user_id: $sr_user_id);

                                $this->accountLedgerUtil->addAccountLedger(
                                    voucher_type_id: 0,
                                    date: $openingBalanceDate,
                                    account_id: $updateAccount->id,
                                    trans_id: $updateAccount->id,
                                    amount: $request->sr_opening_balances[$index] ? $request->sr_opening_balances[$index] : 0,
                                    amount_type: $request->sr_opening_balance_types[$index],
                                    user_id: $sr_user_id,
                                );
                            }
                            $index++;
                        }
                    }

                    $updateAccount->customer_id = $addCustomer->id;
                }

                $updateAccount->save();
            }

            if ($accountGroup->sub_sub_group_number == 10) {

                $request->phone = $request->supplier_phone_no;
                $request->address = $request->supplier_address;

                $updateAccount->phone = $request->supplier_phone_no;
                $updateAccount->address = $request->supplier_address;

                if ($account->supplier) {

                    $this->supplierUtil->updateSupplier($request, $account->supplier);
                } else {

                    $addSupplier = $this->supplierUtil->addSupplier($request, $invoiceVoucherRefIdUtil, $gs);
                    $updateAccount->supplier_id = $addSupplier->id;
                }

                $updateAccount->save();
            }

            if ($accountGroup->sub_sub_group_number != 6) {

                // Update Opening Balance Ledger
                $this->accountLedgerUtil->updateAccountLedger(
                    voucher_type_id: 0,
                    date: $openingBalanceDate,
                    account_id: $updateAccount->id,
                    trans_id: $updateAccount->id,
                    amount: $request->opening_balance ? $request->opening_balance : 0,
                    amount_type: $request->opening_balance_type,
                );
            }

            $account = DB::table('accounts')->where('id', $id)
                ->select('name', 'account_number', 'phone', 'opening_balance', 'balance')
                ->first();

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 17, data_obj: $account);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(['successMsg' => 'Account updated successfully', 'data' => $account]);
    }

    public function ledger(Request $request, $id, $by, $fromDate = null, $toDate = null, $userId = null)
    {
        if (! auth()->user()->can('accounts_ledger')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->accountLedgerUtil->ledgerEntries($request, $id, $by);
        }

        $account = Account::with(['bank:id,name', 'group:id,sub_sub_group_number'])->where('id', $id)->first();

        $userIds = DB::table('account_ledgers')->where('account_ledgers.account_id', $account->id)
            ->select('account_ledgers.user_id')->distinct()->pluck('user_id');

        $users = '';
        if (auth()->user()->is_marketing_user == 0) {

            $users = DB::table('users')->whereIn('id', $userIds)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('finance.accounting.accounts.account_ledgers', compact('account', 'users', 'fromDate', 'toDate', 'userId'));
    }

    public function ledgerPrint(Request $request, $id, $by)
    {
        if (! auth()->user()->can('accounts_ledger')) {

            abort(403, 'Access Forbidden.');
        }

        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $userName = $request->user_name;
        $customerName = $request->customer_name;

        $entries = $this->accountLedgerUtil->ledgerEntriesPrint($request, $id, $by);

        $account = Account::with(['group:id,name', 'bank:id,name'])->where('id', $id)->first();
        $user = DB::table('users')->where('id', $id)->first();

        return view('finance.accounting.accounts.ajax_view.account_ledger_print', compact('entries', 'user', 'account', 'request', 'fromDate', 'toDate', 'userName', 'customerName', 'by'));
    }

    public function delete(Request $request, $accountId)
    {
        if (! auth()->user()->can('accounts_delete')) {

            abort(403, 'Access Forbidden.');
        }

        $deleteAccount = Account::with('accountLedgersWithOutOpeningBalances', 'supplier', 'customer')->where('id', $accountId)->first();

        if ($deleteAccount->is_fixed == 1) {

            return response()->json(['errorMsg' => 'Account is not deletable.']);
        }

        if (count($deleteAccount->accountLedgersWithOutOpeningBalances) > 0) {

            return response()->json(['errorMsg' => 'Account can not be deleted. One or more ledger entries are belonging in this account.']);
        }

        if (! is_null($deleteAccount)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 17, data_obj: $deleteAccount);

            $deleteAccount->delete();
            $deleteAccount?->supplier?->delete();
            $deleteAccount?->customer?->delete();
        }

        return response()->json('Account deleted successfully');
    }

    public function accountClosingBalance(Request $request, $accountId)
    {
        return $amounts = $this->accountUtil->accountClosingBalance($accountId, $request->from_date, $request->to_date);
    }
}
